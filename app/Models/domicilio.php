<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class domicilio extends Model
{
    protected $fillable = [
        'calle',
        'numero_exterior',
        'numero_interior',
        'codigo_postal',
        'colonia',
        'identificacion_id',
    ];
    use HasFactory;
    public function identificacion(){
        return $this->belongsTo(identificacion::class);
    }

    public function colonia(){
        return $this->belongsTo(colonia::class);
    }
}
