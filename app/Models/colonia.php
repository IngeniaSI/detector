<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class colonia extends Model
{
    use HasFactory;
    public function domicilio(){
        return $this->belongsTo(domicilio::class);
    }
}
