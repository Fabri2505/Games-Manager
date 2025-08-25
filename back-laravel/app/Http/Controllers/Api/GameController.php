<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Serie;
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
        $validated = request()->validate([
            'user_id' => 'required|integer|exists:users,id',
            'serie_id' => 'integer|exists:series,id'
        ]);

        Log::debug("user_id: " . $validated['user_id']);
        Log::debug("serie_id: " . ($validated['serie_id'] ?? 'not provided'));

        $games = Game::where('user_id', $validated['user_id'])
            ->when($validated['serie_id'] ?? null, function ($query, $serieId) {
                return $query->where('serie_id', $serieId);
            })
            ->get();
        
        Log::debug("Validado games obtenidos");
        return response()->json($games);
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post(
     *     path="/api/game",
     *     tags={"Games"},
     *     summary="Crear un nuevo juego",
     *     description="Crea un nuevo juego con descripción y monto. La fecha de juego se establece automáticamente.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"descrip","monto","user_id"},
     *             @OA\Property(
     *                 property="descrip",
     *                 type="string",
     *                 maxLength=255,
     *                 description="Descripción del juego",
     *                 example="Torneo de Ajedrez Mensual"
     *             ),
     *             @OA\Property(
     *                 property="monto",
     *                 type="number",
     *                 format="float",
     *                 minimum=0,
     *                 description="Monto del juego",
     *                 example=50.00
     *             ),
     *             @OA\Property(
     *                 property="user_id",
     *                 type="number",
     *                 minimum=1,
     *                 description="Id del jugador que crea",
     *                 example=1
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Juego creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="game",
     *                 ref="#/components/schemas/Game"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="descrip",
     *                     type="array",
     *                     @OA\Items(type="string", example="The descrip field is required.")
     *                 ),
     *                 @OA\Property(
     *                     property="monto",
     *                     type="array",
     *                     @OA\Items(type="string", example="The monto must be at least 0.")
     *                 )
     *             )
     *         )
     *     )
     * )
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

        return response()->json([
            'game' => $game
        ]);
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
    /**
     * @OA\Put(
     *     path="/api/game/cierre",
     *     tags={"Games"},
     *     summary="Actualizar fecha de cierre del juego",
     *     description="Establece la fecha de cierre de un juego. La fecha debe ser posterior al momento actual.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"fec_cierre"},
     *             @OA\Property(
     *                 property="fec_cierre",
     *                 type="string",
     *                 format="datetime",
     *                 description="Fecha y hora de cierre del juego (debe ser futura)",
     *                 example="2024-08-10 18:00:00"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Juego actualizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Juego actualizado exitosamente"),
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/Game"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Juego no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\Game].")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="fec_cierre",
     *                     type="array",
     *                     @OA\Items(type="string", example="The fec cierre must be a date after now.")
     *                 )
     *             )
     *         )
     *     )
     * )
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
            'data' => $game
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
            'data' => $game
        ]);
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
