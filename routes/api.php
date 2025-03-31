<?php
use App\Http\Controllers\AutenticaController;
use App\Http\Controllers\ManipulacaoDadosController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OpenWeatherMapController;
use App\Http\Controllers\HistoricoController;

Route::get('weather', [OpenWeatherMapController::class, 'getWeather'])->middleware('autenticacao.custom');
Route::get('listar', [ManipulacaoDadosController::class, 'listar'])->middleware('autenticacao.custom');
Route::delete('deletar/{nome}', [ManipulacaoDadosController::class, 'deletar'])->middleware('autenticacao.custom');
Route::get('historico', [HistoricoController::class, 'listar'])->middleware('autenticacao.custom');


Route::post('registrar', [AutenticaController::class, 'registrar']);

Route::get('login', [AutenticaController::class, 'login']);
Route::post('logout', [AutenticaController::class, 'logout']);
