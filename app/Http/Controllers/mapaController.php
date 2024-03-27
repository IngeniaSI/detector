<?php

namespace App\Http\Controllers;

use App\Models\domicilio;
use Illuminate\Http\Request;

class mapaController extends Controller
{
    public function index(){
        $puntos = domicilio::all(['latitud', 'longitud']);
        $domicilioArray = [];

        foreach ($puntos as $punto) {
            $coordenadas = array($punto->latitud, $punto->longitud, 'Persona Registrada');
            $domicilioArray[] = $coordenadas;
        }

        return view('mapa', compact('domicilioArray'));
    }
}
