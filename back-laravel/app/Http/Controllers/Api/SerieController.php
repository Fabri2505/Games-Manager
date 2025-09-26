<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SerieResource;
use App\Models\Game;
use App\Models\Serie;
use Illuminate\Http\Request;

class SerieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'is_active' => 'boolean'
        ]);

        $series = Serie::where('user_id', $validated['user_id'])
            ->when($validated['is_active'] ?? null, function ($query, $isActive) {
                return $query->where('is_active', $isActive);
            })
            ->withCount('games')
            ->get();

        return SerieResource::collection($series);
    }

    public function asignGame(Request $request)
    {
        $validated = $request->validate([
            'serie_id' => 'required|integer|exists:series,id',
            'name_game' => 'required|string|max:100',
            'monto' => 'required|numeric|min:0',
            'user_id'=>'required|exists:users,id'
        ]);

        $serie = Serie::where('id', $validated['serie_id'])
            ->where('user_id', $validated['user_id'])
            ->first();

        if (!$serie) {
            return response()->json(['message' => 'Serie not found or does not belong to the user'], 404);
        }

        $validated['fec_juego'] = now();
        $validated['fec_cierre'] = null;

        $game = Game::create([
            'name' => $validated['name_game'],
            'monto' => $validated['monto'],
            'user_id' => $validated['user_id'],
            'serie_id' => $validated['serie_id'],
            'fec_juego' => $validated['fec_juego'],
            'fec_cierre' => $validated['fec_cierre']
        ]);

        return response()->json([
            'message' => 'Nuevo Juego Creado y asignado a la serie', 
            'game' => $game
        ], 201);
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
