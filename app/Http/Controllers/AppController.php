<?php

namespace App\Http\Controllers;

class AppController extends Controller
{
    public function home()
    {
        return redirect(route('dashboard'));
        // return view('welcome');
    }

    public function dashboard()
    {
        return view('dashboard');
    }
}
