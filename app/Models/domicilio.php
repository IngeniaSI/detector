<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class domicilio extends Model
{
    use HasFactory;
    public function identificacion(){
        return $this->belongsTo(identificacion::class);
    }

    public function colonia(){
        return $this->hasOne(colonia::class);
    }
}
