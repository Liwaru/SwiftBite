<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class CustomerPageController extends Controller
{
    public function home(): View
    {
        return view('customer.home');
    }
}
