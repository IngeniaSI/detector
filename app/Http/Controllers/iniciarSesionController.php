<?php

namespace App\Http\Controllers;

use App\Models\bitacora;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class iniciarSesionController extends Controller
{
    public function index(Request $formulario){
        $bitacora = new bitacora();
        $bitacora->accion = 'entrando pantalla iniciar sesion';
        $bitacora->url = url()->current();
        $bitacora->ip = $formulario->ip();
        $bitacora->tipo = 'vista';
        $bitacora->user_id = null;
        $bitacora->save();

        return view('inicioSesion');
    }
    public function validarUsuario(Request $formulario){
        $bitacora = new bitacora();
        $bitacora->accion = 'Iniciando sesion';
        $bitacora->url = url()->current();
        $bitacora->ip = $formulario->ip();
        $bitacora->tipo = 'post';
        $bitacora->user_id = null;
        $bitacora->save();

        $validarSiEliminado = User::where('email', strtoupper($formulario->correo))->first();
        if(isset($validarSiEliminado) && isset($validarSiEliminado->deleted_at)){
            return back()->withErrors(['email' => 'Credenciales incorrectas']);
        }
        if (Auth::attempt(['email' => strtoupper($formulario->correo), 'password' => $formulario->contrasenia])) {
            // Obtener el usuario de la sesion
            $user = auth()->user();
            return redirect()->route('crudUsuario.index');

        } else {
            return back()->withErrors(['email' => 'Credenciales incorrectas']);
        }
    }
    public function cerrarSesion(Request $formulario){
        $user = auth()->user();
        $bitacora = new bitacora();
        $bitacora->accion = 'Cerrando sesion de : ' . $user->email;
        $bitacora->url = url()->current();
        $bitacora->ip = $formulario->ip();
        $bitacora->tipo = 'post';
        $bitacora->user_id = $user->id;
        $bitacora->save();

        Auth::logout();
        return redirect()->route('login');
    }
}
