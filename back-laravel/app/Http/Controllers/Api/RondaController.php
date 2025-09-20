<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Participante;
use App\Models\Ronda;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RondaController extends Controller
{
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "game_id"=> 'required|exists:games,id',
            "participantes" => 'required|array',
            "participantes.*" => 'required|exists:users,id',
        ]);

        Log::debug("Validado parametros correctamente");

        try{
            DB::beginTransaction();

            $ronda = Ronda::create([
                'fec' => now()->toDateString(),
                'hora_ini' => now()->toTimeString(),
                'hora_fin' => null,
                'game_id' => $validated['game_id']
            ]);

            Log::info('Ronda creada');
            Log::info('Ronda creada con ID:', ['id' => $ronda->id]);

            Log::info('Usuarios recibidos:', $request->input('participantes'));
            $existingUsers = User::whereIn('id', $request->input('participantes'))->pluck('id')->toArray();
            Log::info('Usuarios que existen:', $existingUsers);
            
            $participantes = collect($validated['participantes'])->map(function ($userId) use ($ronda) {
                return [
                    'user_id' => $userId,
                    'ronda_id' => $ronda->id,
                    'winner' => false,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            });

            Log::info('Participantes mapeados:', $participantes->toArray());

            Participante::insert($participantes->toArray());

            DB::commit();
            Log::info('A punto de retornar respuesta de éxito');
            return response()->json([
                'success' => true,
                'message' => 'Ronda iniciada exitosamente',
                'data' => $ronda->load('participantes.user')
            ], 201);


        }catch(\Exception $e){

            DB::rollback();

            Log::error('Error al crear ronda:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

        
            return response()->json([
                'success' => false,
                'message' => 'Error al iniciar la ronda',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);

        }
    }

    public function addPlayers(Request $request, $rondaId)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        try {
            DB::beginTransaction();

            // Verificar que la ronda existe y no ha terminado
            $ronda = Ronda::findOrFail($rondaId);
            
            if ($ronda->hora_fin !== null) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pueden agregar participantes a una ronda finalizada'
                ], 400);
            }

            // Filtrar usuarios que ya están en la ronda
            $usuariosExistentes = Participante::where('ronda_id', $rondaId)
                ->whereIn('user_id', $validated['user_ids'])
                ->pluck('user_id')
                ->toArray();

            $usuariosNuevos = array_diff($validated['user_ids'], $usuariosExistentes);

            if (empty($usuariosNuevos)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Todos los usuarios ya están participando en esta ronda'
                ], 400);
            }

            // Crear participantes usando insert masivo
            $participantesData = collect($usuariosNuevos)->map(function ($userId) use ($rondaId) {
                return [
                    'user_id' => $userId,
                    'ronda_id' => $rondaId,
                    'winner' => false,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            });

            Participante::insert($participantesData->toArray());

            // Obtener participantes creados con relaciones
            $participantesCreados = Participante::with('user:id,name,email')
                ->where('ronda_id', $rondaId)
                ->whereIn('user_id', $usuariosNuevos)
                ->get();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => count($usuariosNuevos) . ' participante(s) agregado(s) exitosamente',
                'data' => [
                    'participantes_agregados' => $participantesCreados,
                    'usuarios_duplicados' => $usuariosExistentes
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al agregar participantes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function removePlayer(Request $request, $rondaId)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        try {
            DB::beginTransaction();

            // Verificar que la ronda no ha terminado
            $ronda = Ronda::findOrFail($rondaId);
            
            if ($ronda->hora_fin !== null) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pueden remover participantes de una ronda finalizada'
                ], 400);
            }

            // Buscar el participante
            $participante = Participante::where('user_id', $validated['user_id'])
                ->where('ronda_id', $rondaId)
                ->first();

            if (!$participante) {
                return response()->json([
                    'success' => false,
                    'message' => 'El usuario no está participando en esta ronda'
                ], 404);
            }

            // Verificar que no sea el ganador
            if ($participante->winner) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede remover al ganador de la ronda'
                ], 400);
            }

            $userName = $participante->user->name;
            $participante->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Participante {$userName} removido exitosamente"
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al remover participante',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function setWinner(Request $request, $rondaId)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'win' => 'required|boolean'
        ]);

        try {
            DB::beginTransaction();

            // Verificar que la ronda existe
            $ronda = Ronda::findOrFail($rondaId);

            // Buscar el participante
            $participante = Participante::where('ronda_id', $rondaId)
                ->where('user_id', $validated['user_id'])
                ->first();

            if (!$participante) {
                return response()->json([
                    'success' => false,
                    'message' => 'El usuario no está participando en esta ronda'
                ], 404);
            }

            if ($validated['win']) {
                // Si se marca como ganador, quitar el winner a otros participantes
                Participante::where('ronda_id', $rondaId)
                    ->where('user_id', '!=', $validated['user_id'])
                    ->update(['winner' => false]);

                // Marcar como ganador
                $participante->winner = true;
                $message = 'Ganador establecido exitosamente';
            } else {
                // Quitar como ganador
                $participante->winner = false;
                $message = 'Ganador removido exitosamente';
            }

            $participante->save();

            // Si se establece un ganador, finalizar la ronda automáticamente
            if ($validated['win'] && $ronda->hora_fin === null) {
                $ronda->hora_fin = now()->toTimeString();
                $ronda->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'participante' => $participante->load('user:id,name,email'),
                    'ronda_finalizada' => $validated['win'] && $ronda->wasChanged('hora_fin')
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al establecer ganador',
                'error' => $e->getMessage()
            ], 500);
        }

    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
