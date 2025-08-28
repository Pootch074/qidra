<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PreassessController extends Controller
{
    public function index()
    {
        return view('preassess'); // make sure a Blade file exists at resources/views/preassess.blade.php
    }
}
