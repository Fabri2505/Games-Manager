<?php

use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\RondaController;
use App\Http\Controllers\Api\SerieController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [UserController::class, 'store']);
Route::post('/login', [UserController::class, 'login']);

// Route::middleware('auth:sanctum')->group(function(){
    Route::get('players', [UserController::class,'index'])->name('players.index');
    Route::get('series', [SerieController::class,'index'])->name('series.index');
    Route::post('user', [UserController::class,'store'])->name('user.create');
    
    Route::get('game', [GameController::class,'index'])->name('game.index');
    Route::get('game/{idGame}', [GameController::class,'getGame'])->name('game.getGame');
    Route::get('game/{idGame}/last-ronda', [GameController::class,'getLastRonda'])->name('game.getLastRonda');
    Route::get('game/{idGame}/anality', [GameController::class,'getAnalityGame'])->name('game.getAnalityGame');
    //Route::get('game/actual', [GameController::class,'actual'])->name('game.actual');
    Route::post('game', [GameController::class,'store'])->name('game.store');
    Route::put('game/cierre', [GameController::class,'update'])->name('game.cierre');
    Route::put('game/paused', [GameController::class,'paused'])->name('game.paused');

    Route::post('rondas', [RondaController::class,'store'])->name('ronda.store');
    Route::put('ronda/{rondaId}/set-winner', [RondaController::class,'setWinner'])->name('ronda.setWinner');
    Route::post('ronda/{rondaId}/add-players', [RondaController::class,'addPlayers'])->name('ronda.addPlayers');
    Route::delete('ronda/{rondaId}/remove-player', [RondaController::class,'removePlayer'])->name('ronda.removePlayer');
// });

