<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricoPesquisa extends Model
{
    use HasFactory;

    protected $table = 'historico_pesquisa'; // Nome da tabela

    protected $fillable = ['message']; // Campos permitidos para inserção
}
