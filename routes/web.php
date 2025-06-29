<?php

use App\Http\Controllers\GolpeaoController;
use App\Http\Controllers\ManagerGameController;
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
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
