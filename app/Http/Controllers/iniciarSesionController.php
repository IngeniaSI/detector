<?php

namespace App\Http\Controllers;

use App\Imports\metasSeccionImport;
use App\Imports\personasYDatosImport;
use App\Models\bitacora;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class iniciarSesionController extends Controller
{
    public function index(Request $formulario){
        $user = auth()->user();
        if($user){
            switch ($user->getRoleNames()->first()) {
                case 'SUPER ADMINISTRADOR':
                    return redirect()->route('crudUsuario.index');
                    break;
                case 'ADMINISTRADOR':
                    return redirect()->route('estadistica.index');
                    break;
                case 'SUPERVISOR':
                    return redirect()->route('crudSimpatizantes.index');
                    break;
                case 'CAPTURISTA':
                    return redirect()->route('crudSimpatizantes.index');
                    break;
                case 'CONSULTAS':
                    return redirect()->route('crudSimpatizantes.index');
                    break;
            }
        }
        else{
            $bitacora = new bitacora();
            $bitacora->accion = 'entrando pantalla iniciar sesion';
            $bitacora->url = url()->current();
            $bitacora->ip = $formulario->ip();
            $bitacora->tipo = 'vista';
            $bitacora->user_id = null;
            $bitacora->save();
            return view('inicioSesion');
        }

    }
    public function validarUsuario(Request $formulario){
        $validarSiEliminado = User::where('email', strtoupper($formulario->correo))->first();
        if(!isset($validarSiEliminado) || isset($validarSiEliminado->deleted_at)){
            return back()->withErrors(['email' => 'El correo ingresado es incorrecto']);
        }
        if (Auth::attempt(['email' => strtoupper($formulario->correo), 'password' => $formulario->contrasenia])) {
            $bitacora = new bitacora();
            $bitacora->accion = 'Iniciando sesion';
            $bitacora->url = url()->current();
            $bitacora->ip = $formulario->ip();
            $bitacora->tipo = 'post';
            $bitacora->user_id = null;
            $bitacora->save();
            // Obtener el usuario de la sesion
            $user = auth()->user();
            switch ($validarSiEliminado->getRoleNames()->first()) {
                case 'SUPER ADMINISTRADOR':
                    return redirect()->route('crudUsuario.index');
                    break;
                case 'ADMINISTRADOR':
                    return redirect()->route('estadistica.index');
                    break;
                case 'SUPERVISOR':
                    return redirect()->route('crudSimpatizantes.index');
                    break;
                case 'CAPTURISTA':
                    return redirect()->route('crudSimpatizantes.index');
                    break;
                    case 'CONSULTAS':
                        return redirect()->route('crudSimpatizantes.index');
                        break;
                default:
                    return back()->withErrors(['email' => 'Ocurrió un error con el usuario ingresado, comuniquese con el administrador del sistema.']);
                    break;
            }

        } else {
            return back()->withErrors(['email' => 'La contraseña ingresada es incorrecta']);
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
