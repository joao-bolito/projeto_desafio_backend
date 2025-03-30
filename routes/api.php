<?php

// appid= 169440e27bd116f260e0d9afb46fc542

use App\Http\Controllers\AutenticaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OpenWeatherMapController;

// trazer dados do clima de latitude e longitude
Route::get('weather', [OpenWeatherMapController::class, 'getWeather'])->middleware('autenticacao.custom');

// enviar dados de cadastro de usuário
Route::post('registrar', [AutenticaController::class, 'registrar']);

Route::get('login', [AutenticaController::class, 'login']);
Route::post('logout', [AutenticaController::class, 'logout']);

Route::get('/teste-middleware', function () {
    return response()->json(['message' => 'Middleware funcionando!']);
})->middleware('autenticacao.custom');
