<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class OwnerController extends Controller
{
    public function dashboard(): View
    {
        return view('owner.dashboard');
    }
}
