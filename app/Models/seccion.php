<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class seccion extends Model
{
    protected $fillable = [
        'id',
        'objetivo',
        'poblacion',
    ];
    use HasFactory;
    public function seccionColonia(){
        return $this->hasMany(seccionColonia::class);
    }
    public function distritoLocal(){
        return $this->belongsTo(distritoLocal::class);
    }
    public function identificacions(){
        return $this->hasMany(identificacion::class);
    }
}
