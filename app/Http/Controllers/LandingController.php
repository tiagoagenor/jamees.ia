<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Exibe a página inicial do site
     */
    public function index()
    {
        return view('landing');
    }
}
