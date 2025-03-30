<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WeatherData;

class ManipulacaoDadosController extends Controller
{
    public function listar()
    {
        $dados = WeatherData::all();

        if ($dados->isEmpty()) {
            return response()->json(['message' => 'Nenhum dado encontrado'], 404);
        }

        return response()->json($dados, 200);
    }
}
