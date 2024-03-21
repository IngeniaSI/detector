<?php

namespace App\Http\Controllers;

use App\Models\persona;
use Illuminate\Http\Request;

class bitacoraController extends Controller
{
    public function index(){
        return view('bitacora');
    }
    public function inicializar(){
        return persona::where('deleted_at', null)->get();
    }
}
