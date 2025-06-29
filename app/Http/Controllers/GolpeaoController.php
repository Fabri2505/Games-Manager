<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class GolpeaoController extends Controller
{
    public function index()
    {
        return Inertia::render('home-golpeao');
    }
}
