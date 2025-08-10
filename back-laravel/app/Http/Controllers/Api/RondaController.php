<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Participante;
use App\Models\Ronda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RondaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post(
     *     path="/api/rondas",
     *     tags={"Rondas"},
     *     summary="Crear una nueva ronda",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"game_id","participantes"},
     *             @OA\Property(property="game_id", type="integer", example=1),
     *             @OA\Property(
     *                 property="participantes",
     *                 type="array",
     *                 @OA\Items(type="integer"),
     *                 example={1, 2, 3, 4}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Ronda creada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Ronda iniciada exitosamente"),
     *             @OA\Property(property="data", ref="#/components/schemas/Ronda")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación"
     *     )
     * )
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

    /**
     * @OA\Post(
     *     path="/api/rondas/{ronda}/add-players",
     *     tags={"Rondas"},
     *     summary="Agregar participantes a una ronda",
     *     @OA\Parameter(
     *         name="ronda",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_ids"},
     *             @OA\Property(
     *                 property="user_ids",
     *                 type="array",
     *                 @OA\Items(type="integer"),
     *                 example={5, 7, 9}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Participantes agregados exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="3 participante(s) agregado(s) exitosamente"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="participantes_agregados",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/Participante")
     *                 ),
     *                 @OA\Property(
     *                     property="usuarios_duplicados",
     *                     type="array",
     *                     @OA\Items(type="integer")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación o ronda finalizada"
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/rondas/{ronda}/remove-player",
     *     tags={"Rondas"},
     *     summary="Remover un participante de la ronda",
     *     @OA\Parameter(
     *         name="ronda",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id"},
     *             @OA\Property(property="user_id", type="integer", example=7)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Participante removido exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Participante Juan Pérez removido exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Participante no encontrado"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="No se puede remover al ganador o ronda finalizada"
     *     )
     * )
     */
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

    /**
     * @OA\Patch(
     *     path="/api/rondas/{ronda}/set-winner",
     *     tags={"Rondas"},
     *     summary="Establecer o quitar ganador de la ronda",
     *     @OA\Parameter(
     *         name="ronda",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id","win"},
     *             @OA\Property(property="user_id", type="integer", example=5),
     *             @OA\Property(property="win", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ganador establecido exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Ganador establecido exitosamente"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="participante", ref="#/components/schemas/Participante"),
     *                 @OA\Property(property="ronda_finalizada", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Participante no encontrado"
     *     )
     * )
     */
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
