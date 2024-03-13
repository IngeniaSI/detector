<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class crudUsuariosController extends Controller
{
    public function index(){
        $roles = Role::all(['name']);
        return view('crudUsuarios', compact('roles'));
    }
    public function todosUsuarios(){
        return User::where('deleted_at', null)->get(['id', 'email', 'nombre', 'apellido_paterno', 'apellido_materno']);
    }
    public function obtenerUsuario(User $usuario){
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
        'repetirContrasenia' => 'required|same:contrasenia',
        'rolUsuario' => 'required|not_in:-1',
        ]);

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
        session()->flash('formularioModificarErrores', true);
        $formulario->validate([
            'nombre' => 'required',
            'apellido_paterno' => 'required',
            'apellido_materno' => 'required',
            'correo' => 'required|email',
            'rolUsuario' => 'required|not_in:-1',
            ]);
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
                //VER TEMAS DE COMO CAMBIAR ROLES
                //$usuario->assignRole($formulario->rolUsuario);
                DB::commit();
                session()->forget('formularioCrearErrores');
                return redirect()->route('crudUsuario.index');
            }
            catch(Exception $e){
                DB::rollBack();
                Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
                return back()->withErrors(['errorValidacion' => 'Ha ocurrido un error al registrar el usuario']);
            }
        }
    }
    public function borrarUsuario(User $usuario){
        if(!isset($usuario->deteled_at)){
            try{
                DB::beginTransaction();
                $usuario->deleted_at =  Date("Y-m-d H:i:s");
                $usuario->save();
                DB::commit();
                session()->forget('formularioCrearErrores');
                return redirect()->route('crudUsuario.index');
            }
            catch(Exception $e){
                DB::rollBack();
                Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
                return back()->withErrors(['errorValidacion' => 'Ha ocurrido un error al registrar el usuario']);
            }
        }
        else{
            return back()->withErrors(['errorValidacion' => 'Ha ocurrido un error al registrar el usuario']);
        }
    }
}
