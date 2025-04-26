<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AppController extends Controller
{
    public function dashboard()
    {
        return view('livewire.admin.dashboard');
    }
}
