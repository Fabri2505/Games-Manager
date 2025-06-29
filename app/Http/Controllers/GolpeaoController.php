<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GolpeaoController extends Controller
{
    public function index()
    {
        $players = Player::with('user')
            ->get()
            ->map(function ($player) {
                return [
                    'id' => $player->id,
                    'nombre' => $player->nom,
                    'apellido' => $player->ape,
                    'nombre_completo' => $player->nom . ' ' . $player->ape,
                    'email' => $player->user ? $player->user->email : null,
                    'user' => $player->user
                ];
            });
        return Inertia::render('home-golpeao', [
            'players' => $players
        ]);
    }
}
