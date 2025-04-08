<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Stripecontroller extends Controller
{
    public function index()
    {
        return view('index');
    }
    public function checkout()
    {
        return view('checkout');
    }
    public function success()
    {
        return view('index');
    }
}
