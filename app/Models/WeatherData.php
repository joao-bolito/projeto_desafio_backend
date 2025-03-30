<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherData extends Model
{
    use HasFactory;

    protected $table = 'data_api_weather';

    protected $fillable = [
        'lat', 'lon', 'weather_main', 'weather_description', 'weather_icon',
        'temp', 'feels_like', 'temp_min', 'temp_max', 'pressure', 'humidity',
        'visibility', 'wind_speed', 'wind_deg', 'clouds_all', 'dt', 'country',
        'sunrise', 'sunset', 'timezone', 'city_name'
    ];
}
