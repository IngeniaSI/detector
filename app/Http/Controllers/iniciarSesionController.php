<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class iniciarSesionController extends Controller
{
    public function index(){
        return view('inicioSesion');
    }
    public function validarUsuario(Request $formulario){
        $validarSiEliminado = User::where('email', $formulario->correo)->first();
        if(isset($validarSiEliminado) && isset($validarSiEliminado->deleted_at)){
            return back()->withErrors(['email' => 'Credenciales incorrectas']);
        }
        if (Auth::attempt(['email' => $formulario->correo, 'password' => $formulario->contrasenia])) {
            // Obtener el usuario de la sesion
            $user = auth()->user();
            return redirect()->route('crudUsuario.index');

        } else {
            return back()->withErrors(['email' => 'Credenciales incorrectas']);
        }
    }
    public function cerrarSesion(){
        Auth::logout();
        return redirect()->route('login');
    }
}
