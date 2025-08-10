<?php

use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\RondaController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [UserController::class, 'store']);
Route::post('/login', [UserController::class, 'login']);

// Route::middleware('auth:sanctum')->group(function(){
    Route::get('players', [UserController::class,'index'])->name('players.index');
    Route::post('user', [UserController::class,'store'])->name('user.create');
    Route::post('game', [GameController::class,'store'])->name('game.store');
    Route::put('game/cierre', [GameController::class,'update'])->name('game.cierre');
    Route::post('ronda', [RondaController::class,'store'])->name('ronda.store');
    Route::put('ronda/{ronda}/set-winner', [RondaController::class,'setWinner'])->name('ronda.setWinner');
    Route::post('ronda/{ronda}/add-players', [RondaController::class,'addPlayers'])->name('ronda.addPlayers');
    Route::delete('ronda/{ronda}/remove-player', [RondaController::class,'removePlayer'])->name('ronda.removePlayer');
// });

