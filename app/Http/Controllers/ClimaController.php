<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clima;

class ClimaController extends Controller
{
    public function index(){
        return Clima::all();
    }
}
