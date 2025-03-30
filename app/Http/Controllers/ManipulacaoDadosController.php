<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WeatherData;
use Exception;

class ManipulacaoDadosController extends Controller
{
    public function listar()
    {
        try {
            $dados = WeatherData::all();

            if ($dados->isEmpty()) {
                return response()->json(['message' => 'Nenhum dado encontrado'], 404);
            }

            return response()->json($dados, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao listar dados', 'message' => $e->getMessage()], 500);
        }
    }

    public function deletar($nome)
    {
        try {
            $dado = WeatherData::where('city_name', $nome)->first();

            if (!$dado) {
                return response()->json(['error' => 'Registro não encontrado'], 404);
            }

            $dado->delete();

            return response()->json(['message' => 'Registro deletado com sucesso!'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao deletar dados', 'message' => $e->getMessage()], 500);
        }
    }
}
