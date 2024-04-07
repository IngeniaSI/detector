<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class distritoLocal extends Model
{
    use HasFactory;
    public function seccion(){
        return $this->hasMany(seccion::class);
    }
    public function municipio(){
        return $this->belongsTo(municipio::class);
    }
}
