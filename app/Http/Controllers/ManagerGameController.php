<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ManagerGameController extends Controller
{
    public function index()
    {
        return Inertia::render('manager-games');
    }
}
