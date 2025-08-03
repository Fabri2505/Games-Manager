<?php

use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\GolpeadoController;
use App\Http\Controllers\Api\RondaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('players', [GolpeadoController::class,'index'])->name('players.index');
Route::post('game', [GameController::class,'store'])->name('game.store');
Route::put('game/cierre', [GameController::class,'update'])->name('game.cierre');
Route::post('ronda', [RondaController::class,'store'])->name('ronda.store');
Route::put('ronda/{ronda}/set-winner', [RondaController::class,'setWinner'])->name('ronda.setWinner');
Route::post('ronda/{ronda}/add-players', [RondaController::class,'addPlayers'])->name('ronda.addPlayers');
Route::delete('ronda/{ronda}/remove-players', [RondaController::class,'removePlayer'])->name('ronda.removePlayer');
