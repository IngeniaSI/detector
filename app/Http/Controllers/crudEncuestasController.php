<?php

namespace App\Http\Controllers;

use App\Models\bitacora;
use App\Models\distritoFederal;
use App\Models\distritoLocal;
use App\Models\entidad;
use App\Models\seccion;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class crudEncuestasController extends Controller
{
    public function index(){
        // $user = auth()->user();
        // $bitacora = new bitacora();
        // $bitacora->accion = 'entrando en la vista crud encuestas : ' . $user->email;
        // $bitacora->url = url()->current();
        // $bitacora->ip = $formulario->ip();
        // $bitacora->tipo = 'vista';
        // $bitacora->user_id = $user->id;
        // $bitacora->save();

        // $roles = Role::where('name', '!=', 'SUPER ADMINISTRADOR')->get(['name']);
        // return view('crudEncuestas', compact('roles'));
        return view();
    }
    // public function todasEncuestas(Request $formulario){
    //     $user = auth()->user();
    //     $bitacora = new bitacora();
    //     $bitacora->accion = 'obtener usuarios : ' . $user->email;
    //     $bitacora->url = url()->current();
    //     $bitacora->ip = $formulario->ip();
    //     $bitacora->tipo = 'ajax';
    //     $bitacora->user_id = $user->id;
    //     $bitacora->save();

    //     $niveles = [
    //         'entidades' => entidad::all(['id']),
    //         'distritosFederales' => distritoFederal::all(['id']),
    //         'distritosLocales' => distritoLocal::all(['id']),
    //         'secciones' => seccion::all(['id']),
    //     ];

    //     $usuarios = User::join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
    //     ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
    //     ->where('deleted_at', null)
    //     ->orderBy('id', 'DESC')
    //     ->get(['users.id', 'email', 'nombre', 'apellido_paterno', 'apellido_materno', 'telefono', 'name']);

    //     return array($usuarios, $niveles);
    // }
    // public function obtenerEncuesta(Request $formulario, User $usuario){
    //     $user = auth()->user();
    //     $bitacora = new bitacora();
    //     $bitacora->accion = 'obtener encuesta para el formulario modificar : ';
    //     $bitacora->url = url()->current();
    //     $bitacora->ip = $formulario->ip();
    //     $bitacora->tipo = 'ajax';
    //     $bitacora->user_id = $user->id;
    //     $bitacora->save();

    //     if(isset($usuario) && !isset($usuario->deteled_at)){
    //         unset($usuario->password);
    //         return [$usuario, $usuario->getRoleNames()->first()];
    //     }
    //     return null;
    // }
    // public function crearEncuesta(Request $formulario){
    //     session()->flash('formularioCrearErrores', true);
    //     $formulario->validate([
    //         'nombre' => 'required',
    //         'apellido_paterno' => 'required',
    //         'correo' => 'required|email',
    //         'contrasenia' => 'required',
    //         'rolUsuario' => 'required|not_in:-1',
    //         'nivelAcceso' => 'not_in:-1',
    //     ]);

    //     $user = auth()->user();
    //     $bitacora = new bitacora();
    //     $bitacora->accion = 'Crear nuevo usuario';
    //     $bitacora->url = url()->current();
    //     $bitacora->ip = $formulario->ip();
    //     $bitacora->tipo = 'post';
    //     $bitacora->user_id = $user->id;
    //     $bitacora->save();

    //     $buscarUsuario = User::where('email', strtoupper($formulario->correo))->first();
    //     if(!isset($buscarUsuario)){
    //         try{
    //             DB::beginTransaction();
    //             $usuario = new User();
    //             $usuario->nombre = strtoupper($formulario->nombre);
    //             $usuario->apellido_paterno = strtoupper($formulario->apellido_paterno);
    //             $usuario->apellido_materno = strtoupper($formulario->apellido_materno);
    //             $usuario->telefono = $formulario->telefono;
    //             $usuario->email = strtoupper($formulario->correo);
    //             $usuario->password = Hash::make($formulario->contrasenia);
    //             $usuario->nivel_acceso = strtoupper($formulario->nivelAcceso);
    //             if($formulario->nivelAcceso != 'TODO'){
    //                 if(count($formulario->niveles) > 0){
    //                     $nivelesConcatenados = '';
    //                     foreach ($formulario->niveles as $nivel) {
    //                         $nivelesConcatenados .= $nivel . ',';
    //                     }
    //                     $nivelesConcatenados = substr($nivelesConcatenados, 0, -1);
    //                     $usuario->niveles = $nivelesConcatenados;
    //                 }
    //                 else{
    //                     return back()->withErrors(['errorValidacion' => 'Debes de seleccionar al menos un nivel (entidad, distrito federal, distrito local o sección)'])->withInput();
    //                 }
    //             }
    //             $usuario->save();
    //             $usuario->assignRole($formulario->rolUsuario);
    //             DB::commit();
    //             session()->forget('formularioCrearErrores');
    //             session()->flash('mensajeExito', 'Usuario creado con exito');
    //             return redirect()->route('crudUsuario.index');
    //         }
    //         catch(Exception $e){
    //             DB::rollBack();
    //             Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
    //             return back()->withErrors(['errorValidacion' => 'Ha ocurrido un error al registrar el usuario'])->withInput();
    //         }
    //     }
    //     else{
    //         return back()->withErrors(['errorValidacion' => 'El correo ya se encuentra registrado'])->withInput();
    //     }

    // }
    // public function editarencuesta(Request $formulario, User $usuario){
    //     session()->flash('usuarioAModificar', $usuario->id);
    //     session()->flash('formularioModificarErrores', true);
    //     $formulario->validate([
    //         'nombre' => 'required',
    //         'apellido_paterno' => 'required',
    //         'correo' => 'required|email',
    //         'rolUsuario' => 'not_in:-1',
    //         'nivelAcceso' => 'not_in:-1',
    //         ]);

    //         $user = auth()->user();
    //         $bitacora = new bitacora();
    //         $bitacora->accion = 'Modificando usuario : ' . $usuario->email;
    //         $bitacora->url = url()->current();
    //         $bitacora->ip = $formulario->ip();
    //         $bitacora->tipo = 'post';
    //         $bitacora->user_id = $user->id;
    //         $bitacora->save();

    //     if(!isset($usuario->deteled_at)){
    //         try{
    //             DB::beginTransaction();
    //             $usuario->nombre = strtoupper($formulario->nombre);
    //             $usuario->apellido_paterno = strtoupper($formulario->apellido_paterno);
    //             $usuario->apellido_materno = strtoupper($formulario->apellido_materno);
    //             $usuario->telefono = $formulario->telefono;
    //             $usuario->email = strtoupper($formulario->correo);
    //             $usuario->nivel_acceso = strtoupper($formulario->nivelAcceso);
    //             if($formulario->nivelAcceso != 'TODO'){
    //                 if(count($formulario->niveles) > 0){
    //                     $nivelesConcatenados = '';
    //                     foreach ($formulario->niveles as $nivel) {
    //                         $nivelesConcatenados .= $nivel . ',';
    //                     }
    //                     $nivelesConcatenados = substr($nivelesConcatenados, 0, -1);
    //                     $usuario->niveles = $nivelesConcatenados;
    //                 }
    //                 else{
    //                     return back()->withErrors(['errorValidacion' => 'Debes de seleccionar al menos un nivel (entidad, distrito federal, distrito local o sección)'])->withInput();
    //                 }
    //             }
    //             if(isset($formulario->contrasenia) && $formulario->contrasenia != ""){
    //                 $usuario->password = Hash::make($formulario->contrasenia);
    //             }
    //             $usuario->save();

    //             $nombreRol = $usuario->getRoleNames()->first();
    //             //VALIDAR NO ELIMIAR SUPER USUARIO
    //             if($nombreRol != $formulario->rolUsuario && $nombreRol != 'SUPER ADMINISTRADOR'){
    //                 $usuario->removeRole($nombreRol);
    //                 $usuario->assignRole($formulario->rolUsuario);
    //             }
    //             DB::commit();
    //             session()->forget('formularioModificarErrores');
    //             session()->flash('mensajeExito', 'Usuario editado con éxito');
    //             return redirect()->route('crudUsuario.index');
    //         }
    //         catch(Exception $e){
    //             DB::rollBack();
    //             Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
    //             return back()->withErrors(['errorValidacion' => 'Ha ocurrido un error al registrar el usuario'])->withInput();
    //         }
    //     }
    // }
    // public function borrarEncuesta(Request $formulario, User $usuario){
    //     $nombreRol = $usuario->getRoleNames()->first();
    //     if($nombreRol == 'SUPER ADMINISTRADOR'){

    //         $user = auth()->user();
    //         $bitacora = new bitacora();
    //         $bitacora->accion = 'Se intento borrar el super usuario : ' . $usuario->email;
    //         $bitacora->url = url()->current();
    //         $bitacora->ip = $formulario->ip();
    //         $bitacora->tipo = 'post';
    //         $bitacora->user_id = $user->id;
    //         $bitacora->save();

    //         return back()->withErrors(['errorBorrar' => 'No se puede borrar al super usuario']);
    //     }

    //     $user = auth()->user();
    //     $bitacora = new bitacora();
    //     $bitacora->accion = 'Borrando el usuario : ' . $usuario->email;
    //     $bitacora->url = url()->current();
    //     $bitacora->ip = $formulario->ip();
    //     $bitacora->tipo = 'post';
    //     $bitacora->user_id = $user->id;
    //     $bitacora->save();

    //     if(!isset($usuario->deteled_at)){
    //         try{
    //             DB::beginTransaction();
    //             $usuario->deleted_at =  Date("Y-m-d H:i:s");
    //             $usuario->save();
    //             DB::commit();
    //             session()->flash('mensajeExito', 'Usuario eliminado con éxito');
    //             return redirect()->route('crudUsuario.index');
    //         }
    //         catch(Exception $e){
    //             DB::rollBack();
    //             Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
    //             return back()->withErrors(['errorBorrar' => 'Ha ocurrido un error al registrar el usuario']);
    //         }
    //     }
    //     else{
    //         return back()->withErrors(['errorBorrar' => 'Ha ocurrido un error al registrar el usuario']);
    //     }
    // }
}
