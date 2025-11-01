<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class persona extends Model
{
    protected $fillable = [
        'folio',
        'promotor_id',
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'genero',
        'fecha_nacimiento',
        'edadPromedio',
        'telefono_celular',
        'telefono_fijo',
        'correo',
        'facebook',
        'escolaridad',
        'afiliado',
        'simpatizante',
        'programa',
        'funcion_campania',
        'rol_designado',
        'rol_id',
        'supervisado',
        'observaciones',
        'etiquetas',
    ];

    use HasFactory;
    public function identificacion(){
        return $this->hasOne(identificacion::class);
    }

    public function promotor(){
        return $this->belongsTo(persona::class, 'persona_id');
    }
}
