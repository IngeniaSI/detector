<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class respuesta extends Model
{
    use HasFactory;
    public function encuesta(){
        return $this->belongsTo(encuesta::class);
    }
}
