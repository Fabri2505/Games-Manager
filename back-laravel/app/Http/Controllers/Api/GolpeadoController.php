<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Players",
 *     description="Gestión de jugadores"
 * )
 */
class GolpeadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/players",
     *     tags={"Players"},
     *     summary="Listar todos los jugadores",
     *     description="Obtiene una lista completa de todos los jugadores registrados en el sistema",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de jugadores obtenida exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="players",
     *                 type="array",
     *                 description="Array de jugadores",
     *                 @OA\Items(ref="#/components/schemas/User")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Internal server error")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $users = User::all();

        return response()->json([
            'players' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
