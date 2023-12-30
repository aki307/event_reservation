<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller
{
    // 暫定的
    public function index()
    {
        return view('events.index');
    }
}
