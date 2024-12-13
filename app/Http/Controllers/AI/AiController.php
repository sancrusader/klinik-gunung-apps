<?php

namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AiController extends Controller
{
    public function index(){
        return Inertia::render('AI/Index');
    }
}
