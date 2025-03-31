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
            // Recupera o nome da cidade via JSON
            $appid = "169440e27bd116f260e0d9afb46fc542";
            $name = $request->input('name');  // Agora está vindo no corpo da requisição JSON

            if (empty($name)) {
                return response()->json(['error' => 'Nome da cidade é obrigatório'], 400);
            }

            // Chama a API do Nominatim para obter as coordenadas
            $urlNominatim = "https://nominatim.openstreetmap.org/search?format=json&q={$name}";

            //Api Nominatim exige user-agent no cabeçalho da requisição
            $response = Http::withHeaders([
                'User-Agent' => 'YourAppName/1.0 (https://yourwebsite.com)'
            ])->get($urlNominatim);

            if ($response->failed()) {
                return response()->json(['error' => 'Erro ao obter coordenadas da cidade'], $response->status());
            }

            $nominatimData = $response->json();

            // Verifica se a resposta do Nominatim contém resultados válidos
            if (empty($nominatimData)) {
                return response()->json(['error' => 'Cidade não encontrada'], 404);
            }

            // Extrai a latitude e longitude da resposta do Nominatim
            $latitude = $nominatimData[0]['lat'];
            $longitude = $nominatimData[0]['lon'];

            // Verifica se os dados de clima já estão no banco
            $weatherData = WeatherData::where('city_name', $name)->first();

            if ($weatherData) {
                // Se os dados já existirem, atualize-os
                $url = "https://api.openweathermap.org/data/2.5/weather?lat={$latitude}&lon={$longitude}&appid={$appid}&units=metric";
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

                HistoricoPesquisa::create([
                    'message' => "Atualizado dado da cidade {$name}"
                ]);

                return response()->json(['message' => 'Dados atualizados com sucesso!', 'data' => $weatherData], 200);
            }

            // Se não houver dados, busque novos dados do clima
            $url = "https://api.openweathermap.org/data/2.5/weather?lat={$latitude}&lon={$longitude}&appid={$appid}&units=metric";
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

            HistoricoPesquisa::create([
                'message' => "Salvo dado da cidade {$name}"
            ]);

            return response()->json(['message' => 'Dados do clima salvos com sucesso!', 'data' => $weather], 201);
        } catch (Exception $e) {
            HistoricoPesquisa::create([
                'message' => "Erro ao salvar dado da cidade {$name}"
            ]);
            return response()->json(['error' => 'Erro ao fazer contato com a API externa', 'message' => $e->getMessage()], 500);
        }
    }
}
