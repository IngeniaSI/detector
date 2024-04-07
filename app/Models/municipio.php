<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class municipio extends Model
{
    use HasFactory;
    public function distritoLocal(){
        return $this->hasMany(distritoLocal::class);
    }
    public function distritoFederal(){
        return $this->belongsTo(distritoFederal::class);
    }
}
