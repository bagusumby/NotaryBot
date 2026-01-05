<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BotTrainingController extends Controller
{
    public function index()
    {
        return view('bot-training');
    }
}
