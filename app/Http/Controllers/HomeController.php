<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Landing page aplikasi.
     */
    public function index(): View
    {
        return view('home');
    }
}
