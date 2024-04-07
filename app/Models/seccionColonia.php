<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class seccionColonia extends Model
{
    use HasFactory;
    public function colonia(){
        return $this->belongsTo(colonia::class);
    }
    public function seccion(){
        return $this->belongsTo(seccion::class);
    }
}
