<?php

namespace App\Http\Controllers;

use App\Models\bitacora;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class crudUsuariosController extends Controller
{
    public function index(Request $formulario){
        $user = auth()->user();
        $bitacora = new bitacora();
        $bitacora->accion = 'entrando en la vista crud usuario : ' . $user->email;
        $bitacora->url = url()->current();
        $bitacora->ip = $formulario->ip();
        $bitacora->tipo = 'vista';
        $bitacora->user_id = $user->id;
        $bitacora->save();

        $roles = Role::where('name', '!=', 'SUPER_ADMINISTRADOR')->get(['name']);
        return view('crudUsuarios', compact('roles'));
    }
    public function todosUsuarios(Request $formulario){
        $user = auth()->user();
        $bitacora = new bitacora();
        $bitacora->accion = 'obtener usuarios : ' . $user->email;
        $bitacora->url = url()->current();
        $bitacora->ip = $formulario->ip();
        $bitacora->tipo = 'ajax';
        $bitacora->user_id = $user->id;
        $bitacora->save();

        return User::where('deleted_at', null)->get(['id', 'email', 'nombre', 'apellido_paterno', 'apellido_materno']);
    }
    public function obtenerUsuario(Request $formulario, User $usuario){
        $user = auth()->user();
        $bitacora = new bitacora();
        $bitacora->accion = 'obtener usuario para el formulario modificar : ';
        $bitacora->url = url()->current();
        $bitacora->ip = $formulario->ip();
        $bitacora->tipo = 'ajax';
        $bitacora->user_id = $user->id;
        $bitacora->save();

        if(isset($usuario) && !isset($usuario->deteled_at)){
            unset($usuario->password);
            return [$usuario, $usuario->getRoleNames()->first()];
        }
        return null;
    }
    public function crearUsuario(Request $formulario){
        session()->flash('formularioCrearErrores', true);
        $formulario->validate([
            'nombre' => 'required',
            'apellido_paterno' => 'required',
            'apellido_materno' => 'required',
            'correo' => 'required|email',
            'contrasenia' => 'required',
            'rolUsuario' => 'required|not_in:-1',
        ]);

        $user = auth()->user();
        $bitacora = new bitacora();
        $bitacora->accion = 'Crear nuevo usuario';
        $bitacora->url = url()->current();
        $bitacora->ip = $formulario->ip();
        $bitacora->tipo = 'post';
        $bitacora->user_id = $user->id;
        $bitacora->save();

        $buscarUsuario = User::where('email', $formulario->correo)->first();
        if(!isset($buscarUsuario)){
            try{
                DB::beginTransaction();
                $usuario = new User();
                $usuario->nombre = $formulario->nombre;
                $usuario->apellido_paterno = $formulario->apellido_paterno;
                $usuario->apellido_materno = $formulario->apellido_materno;
                $usuario->email = $formulario->correo;
                $usuario->password = Hash::make($formulario->contrasenia);
                $usuario->save();
                $usuario->assignRole($formulario->rolUsuario);
                DB::commit();
                session()->forget('formularioCrearErrores');
                session()->flash('mensaje', 'Usuario creado con exito');
                return redirect()->route('crudUsuario.index');
            }
            catch(Exception $e){
                DB::rollBack();
                Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
                return back()->withErrors(['errorValidacion' => 'Ha ocurrido un error al registrar el usuario']);
            }
        }
        else{
            return back()->withErrors(['errorValidacion' => 'El correo ya se encuentra registrado']);
        }

    }
    public function editarUsuario(Request $formulario, User $usuario){
        session()->flash('usuarioAModificar', $usuario->id);
        session()->flash('formularioModificarErrores', true);
        $formulario->validate([
            'nombre' => 'required',
            'apellido_paterno' => 'required',
            'apellido_materno' => 'required',
            'correo' => 'required|email',
            'rolUsuario' => 'not_in:-1',
            ]);

            $user = auth()->user();
            $bitacora = new bitacora();
            $bitacora->accion = 'Modificando usuario : ' . $usuario->email;
            $bitacora->url = url()->current();
            $bitacora->ip = $formulario->ip();
            $bitacora->tipo = 'post';
            $bitacora->user_id = $user->id;
            $bitacora->save();

        if(!isset($usuario->deteled_at)){
            try{
                DB::beginTransaction();
                $usuario->nombre = $formulario->nombre;
                $usuario->apellido_paterno = $formulario->apellido_paterno;
                $usuario->apellido_materno = $formulario->apellido_materno;
                $usuario->email = $formulario->correo;
                if(isset($formulario->contrasenia) && $formulario->contrasenia != ""){
                    $usuario->password = Hash::make($formulario->contrasenia);
                }
                $usuario->save();

                $nombreRol = $usuario->getRoleNames()->first();
                //VALIDAR NO ELIMIAR SUPER USUARIO
                if($nombreRol != $formulario->rolUsuario && $nombreRol != 'SUPER_ADMINISTRADOR'){
                    $usuario->removeRole($nombreRol);
                    $usuario->assignRole($formulario->rolUsuario);
                }
                DB::commit();
                session()->forget('formularioModificarErrores');
                session()->flash('mensaje', 'Usuario editado con exito');
                return redirect()->route('crudUsuario.index');
            }
            catch(Exception $e){
                DB::rollBack();
                Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
                return back()->withErrors(['errorValidacion' => 'Ha ocurrido un error al registrar el usuario']);
            }
        }
    }
    public function borrarUsuario(Request $formulario, User $usuario){
        $nombreRol = $usuario->getRoleNames()->first();
        if($nombreRol == 'SUPER_ADMINISTRADOR'){

            $user = auth()->user();
            $bitacora = new bitacora();
            $bitacora->accion = 'Se intento borrar el super usuario : ' . $usuario->email;
            $bitacora->url = url()->current();
            $bitacora->ip = $formulario->ip();
            $bitacora->tipo = 'post';
            $bitacora->user_id = $user->id;
            $bitacora->save();

            return back()->withErrors(['errorBorrar' => 'No se puede borrar al super usuario']);
        }

        $user = auth()->user();
        $bitacora = new bitacora();
        $bitacora->accion = 'Borrando el usuario : ' . $usuario->email;
        $bitacora->url = url()->current();
        $bitacora->ip = $formulario->ip();
        $bitacora->tipo = 'post';
        $bitacora->user_id = $user->id;
        $bitacora->save();

        if(!isset($usuario->deteled_at)){
            try{
                DB::beginTransaction();
                $usuario->deleted_at =  Date("Y-m-d H:i:s");
                $usuario->save();
                DB::commit();
                session()->flash('mensaje', 'Usuario eliminado con exito');
                return redirect()->route('crudUsuario.index');
            }
            catch(Exception $e){
                DB::rollBack();
                Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
                return back()->withErrors(['errorBorrar' => 'Ha ocurrido un error al registrar el usuario']);
            }
        }
        else{
            return back()->withErrors(['errorBorrar' => 'Ha ocurrido un error al registrar el usuario']);
        }
    }
}
