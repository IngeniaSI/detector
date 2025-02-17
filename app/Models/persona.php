<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class persona extends Model
{
    use HasFactory;
    public function identificacion(){
        return $this->hasOne(identificacion::class);
    }

    public function promotor(){
        return $this->belongsTo(persona::class, 'persona_id');
    }
}
