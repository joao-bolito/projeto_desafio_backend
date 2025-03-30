<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HistoricoPesquisa;
use Exception;

class HistoricoController extends Controller
{
    public function listar(){
        try{
            $registros = HistoricoPesquisa::all();

        return response()->json($registros);
        }catch (Exception $e) {
            return response()->json(['error' => 'Erro ao exibir o histórico', 'message' => $e->getMessage()], 500);
        }
    }
}
