<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;

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
        //
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
     *             required={"descrip","monto"},
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
            'descrip' => 'required|string|max:255',
            'monto' => 'required|numeric|min:0'
        ]);

        $validated['fec_juego'] = now();
        $validated['fec_cierre'] = null;

        $game = Game::create($validated);

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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
