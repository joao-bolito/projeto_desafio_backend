<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\WeatherData;
use App\Models\HistoricoPesquisa;
use Exception;

class OpenWeatherMapController extends Controller
{
    public function getWeather(Request $request)
    {
        try {
            $appid = "169440e27bd116f260e0d9afb46fc542";
            $name = $request->input('name');

            if (empty($name)) {
                return response()->json(['error' => 'Nome da cidade é obrigatório'], 400);
            }

            $weatherData = WeatherData::where('city_name', $name)->first();

            if ($weatherData) {
                $url = "https://api.openweathermap.org/data/2.5/weather?q={$name}&appid={$appid}&units=metric";
                $response = Http::get($url);

                if ($response->failed()) {
                    return response()->json(['error' => 'Erro ao obter dados do clima'], $response->status());
                }

                $data = $response->json();
                $weatherData->update([
                    'lat' => $data['coord']['lat'],
                    'lon' => $data['coord']['lon'],
                    'weather_main' => $data['weather'][0]['main'],
                    'weather_description' => $data['weather'][0]['description'],
                    'weather_icon' => $data['weather'][0]['icon'],
                    'temp' => $data['main']['temp'],
                    'feels_like' => $data['main']['feels_like'],
                    'temp_min' => $data['main']['temp_min'],
                    'temp_max' => $data['main']['temp_max'],
                    'pressure' => $data['main']['pressure'],
                    'humidity' => $data['main']['humidity'],
                    'visibility' => $data['visibility'],
                    'wind_speed' => $data['wind']['speed'],
                    'wind_deg' => $data['wind']['deg'],
                    'clouds_all' => $data['clouds']['all'],
                    'dt' => $data['dt'],
                    'country' => $data['sys']['country'],
                    'sunrise' => $data['sys']['sunrise'],
                    'sunset' => $data['sys']['sunset'],
                    'timezone' => $data['timezone']
                ]);

                historicoPesquisa::create([
                    'message' => "Atualizado dado da cidade {$name}"
                ]);

                return response()->json(['message' => 'Dados atualizados com sucesso!', 'data' => $weatherData], 200);
            }

            $url = "https://api.openweathermap.org/data/2.5/weather?q={$name}&appid={$appid}&units=metric";
            $response = Http::get($url);

            if ($response->failed()) {
                return response()->json(['error' => 'Erro ao obter dados do clima'], $response->status());
            }

            $data = $response->json();
            $weather = WeatherData::create([
                'lat' => $data['coord']['lat'],
                'lon' => $data['coord']['lon'],
                'weather_main' => $data['weather'][0]['main'],
                'weather_description' => $data['weather'][0]['description'],
                'weather_icon' => $data['weather'][0]['icon'],
                'temp' => $data['main']['temp'],
                'feels_like' => $data['main']['feels_like'],
                'temp_min' => $data['main']['temp_min'],
                'temp_max' => $data['main']['temp_max'],
                'pressure' => $data['main']['pressure'],
                'humidity' => $data['main']['humidity'],
                'visibility' => $data['visibility'],
                'wind_speed' => $data['wind']['speed'],
                'wind_deg' => $data['wind']['deg'],
                'clouds_all' => $data['clouds']['all'],
                'dt' => $data['dt'],
                'country' => $data['sys']['country'],
                'sunrise' => $data['sys']['sunrise'],
                'sunset' => $data['sys']['sunset'],
                'timezone' => $data['timezone'],
                'city_name' => $data['name']
            ]);

            historicoPesquisa::create([
                'message' => "Salvo dado da cidade {$name}"
            ]);

            return response()->json(['message' => 'Dados do clima salvos com sucesso!', 'data' => $weather], 201);
        } catch (Exception $e) {
            historicoPesquisa::create([
                'message' => "Erro ao salvar dado da cidade {$name}"
            ]);
            return response()->json(['error' => 'Erro ao fazer contato com a API externa', 'message' => $e->getMessage()], 500);
        }
    }
}
