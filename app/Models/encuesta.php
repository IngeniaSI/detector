<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class encuesta extends Model
{
    use HasFactory;
    public function respuestas(){
        return $this->hasMany(respuesta::class);
    }
}
