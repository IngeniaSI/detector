<?php

namespace App\Http\Controllers;

use App\Models\domicilio;
use Illuminate\Http\Request;

class mapaController extends Controller
{
    public function index(){
        $user = auth()->user();
        if($user->getRoleNames()->first() == 'SUPER ADMINISTRADOR' || $user->getRoleNames()->first() == 'ADMINISTRADOR'){
            $puntos = domicilio::all(['latitud', 'longitud']);
        }
        else{
            // $user = auth()->user();
            // // return $user;
            // $niveles = isset($user->niveles) ? explode( ',', $user->niveles) : null;
            // return $niveles; //APLICAR TRIM A CADA NIVEL
            $puntos = domicilio::all(['latitud', 'longitud']);
        }
        $domicilioArray = [];

        foreach ($puntos as $punto) {
            $coordenadas = array($punto->latitud, $punto->longitud);
            $domicilioArray[] = $coordenadas;
        }

        return view('mapa', compact('domicilioArray'));
    }
}
