<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class identificacion extends Model
{
    use HasFactory;
    public function persona(){
        return $this->belongsTo(persona::class);
    }

    public function domicilio(){
        return $this->hasOne(domicilio::class);
    }
    public function seccion(){
        return $this->belongsTo(seccion::class);
    }
}
