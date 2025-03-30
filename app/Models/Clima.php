<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clima extends Model
{
    use HasFactory;
    protected $fillable = [
        'pais',
        'latitude',
        'longitude',
        'weather_data',
    ];
}


