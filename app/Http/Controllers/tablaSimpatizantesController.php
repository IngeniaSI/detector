<?php

namespace App\Http\Controllers;

use App\Models\persona;
use Illuminate\Http\Request;

class tablaSimpatizantesController extends Controller
{
    public function index(){
        return view('tablaSimpatizantes');
    }
    public function inicializar(){
        return persona::where('deleted_at', null)->get();
    }
}
