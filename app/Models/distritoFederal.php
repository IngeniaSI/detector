<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class distritoFederal extends Model
{
    use HasFactory;
    public function municipios(){
        return $this->hasMany(municipio::class);
    }
    public function entidad(){
        return $this->belongsTo(entidad::class);
    }
}
