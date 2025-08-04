<?php

use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\GolpeadoController;
use App\Http\Controllers\Api\RondaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});