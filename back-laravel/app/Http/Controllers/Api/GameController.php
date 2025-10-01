<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GameResource;
use App\Http\Resources\RondaResource;
use App\Models\Game;
use App\Models\Serie;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Tag(
 *     name="Games",
 *     description="Gestión de juegos"
 * )
 */
class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $validated = request()->validate([
                'user_id' => 'required|integer|exists:users,id',
                'serie_id' => 'nullable|integer|exists:series,id' // nullable
            ]);

            $games = Game::where('user_id', $validated['user_id'])
                ->when($validated['serie_id'] ?? null, function ($query, $serieId) {
                    return $query->where('serie_id', $serieId);
                })
                ->get();
            
            return GameResource::collection($games);

        } catch (\Exception $e) {
            Log::error('Error en GameController@index: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }

    }

    public function getGame($idGame)
    {
        try {
            $game = Game::findOrFail($idGame);

            return response()->json([
                'success' => true,
                'data' => new GameResource($game)
            ]);
        } catch (\Exception $e) {
            Log::error('Error en GameController@getGame: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_serie'=> 'string|max:100',
            'name_game' => 'required|string|max:100',
            'monto' => 'required|numeric|min:0',
            'user_id'=>'required|exists:users,id'
        ]);

        $validated['fec_juego'] = now();
        $validated['fec_cierre'] = null;

        $serie = Serie::create([
            'name'=>$validated['name_serie'],
            'user_id'=>$validated['user_id'],
            'is_active'=>true
        ]);

        $game = Game::create([
            'name' => $validated['name_game'],
            'monto' => $validated['monto'],
            'fec_juego' => $validated['fec_juego'],
            'fec_cierre' => $validated['fec_cierre'],
            'user_id' => $validated['user_id'],
            'serie_id'=>$serie->id
        ]);

        return new GameResource($game);
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
        $validated = $request->validate([
            'fec_cierre' => 'required|date|after:now'
        ]);

        $game = Game::findOrFail($id);
        $game->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Juego actualizado exitosamente', 
            'data' => new GameResource($game)
        ]);
    }

    /**
     * Pausar o reanudar el juego.
     */
    public function paused(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'paused' => 'required|boolean'
        ]);

        $game = Game::findOrFail($validated['game_id']);

        $game->pausado = $validated['paused'];
        $game->save();  
        return response()->json([
            'success' => true,
            'message' => $validated['paused'] ? 'Juego pausado' : 'Juego reanudado',
            'data' => new GameResource($game)
        ]);
    }

    public function getLastRonda(string $game_id)
    {
        try{
            $game = Game::findOrFail($game_id); // Verifica que el juego exista

            $lastRonda = $game->rondas()->with('participantes.user')->orderBy('created_at', 'desc')->first();

            if (!$lastRonda) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay rondas para este juego.'
                ], 404);
            }else {
                return response()->json([
                    'success' => true,
                    'message' => 'Última ronda obtenida exitosamente',
                    'game'=>new GameResource($game),
                    'data' => new RondaResource($lastRonda),
                    'nro_ronda' => $game->rondas()->count()
                ]);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Juego no encontrado',
                'error' => 'El juego con ID ' . $game_id . ' no existe: '
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor',
                'error' => $e->getMessage()
            ], 500);
        }
        
    }

    public function getAnalityGame(string $game_id)
    {
        $game = Game::findOrFail($game_id); // Verifica que el juego exista
        $totalRondas = $game->rondas()->count();

        $racha_result = $game->calcularJugadorEnRacha();

        $liderGame = $game->getLiderGame();

        return response()->json([
            'success' => true,
            'message' => 'Análisis del juego obtenido exitosamente',
            'racha' => $racha_result,
            'total_rondas' => $totalRondas,
            'lider' => $liderGame
        ]);
    }

}
