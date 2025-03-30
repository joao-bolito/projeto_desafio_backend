<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WeatherData;
use App\Models\HistoricoPesquisa;

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

            historicoPesquisa::create([
                'message' => "Listando dados"
            ]);

            return response()->json($dados, 200);
        } catch (Exception $e) {
            historicoPesquisa::create([
                'message' => "Erro ao listar dados"
            ]);
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

            historicoPesquisa::create([
                'message' => "Dado deletado da cidade $nome"
            ]);

            return response()->json(['message' => 'Registro deletado com sucesso!'], 200);
        } catch (Exception $e) {
            historicoPesquisa::create([
                'message' => "Erro ao deletar dado da cidade {$nome}"
            ]);
            return response()->json(['error' => 'Erro ao deletar dados', 'message' => $e->getMessage()], 500);
        }
    }
}
