<?php

namespace App\Http\Controllers;

use App\Models\bitacora;
use App\Models\persona;
use Illuminate\Http\Request;

class bitacoraController extends Controller
{
    public function index(){
        $query = bitacora::where('id', '!=', 'null')->orderBy('created_at', 'desc')->get(['created_at', 'accion', 'url', 'ip', 'user_id']);
        // $roles = bitacora::where('name', '!=', 'SUPER_ADMINISTRADOR')->get(['name']);
        return view('bitacora', compact('query'));
    }
    // public function inicializar(){
    //     return persona::where('deleted_at', null)->get();
    // }
}
