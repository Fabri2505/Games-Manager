<?php

use App\Http\Controllers\GolpeaoController;
use App\Http\Controllers\ManagerGameController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use PhpParser\Node\Expr\PostDec;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('manager-games', [ManagerGameController::class, 'index'])->name('managerGames');
    Route::get('golpeao', [GolpeaoController::class, 'index'])->name('golpeao');
    //Route::get('players',[PlayerController::class, 'index'])->name('players');
    //Route::post('players',[PlayerController::class, 'store'])->name('players');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
