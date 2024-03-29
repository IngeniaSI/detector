<?php

namespace App\Http\Controllers;

use App\Models\bitacora;
use App\Models\persona;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class tablaSimpatizantesController extends Controller
{
    public function index(){
        return view('tablaSimpatizantes');
    }
    public function inicializar(){
        try {
            return persona::join('identificacions', 'personas.id', '=', 'identificacions.persona_id')
            ->join('domicilios', 'domicilios.identificacion_id', '=', 'identificacions.id')
            ->join('colonias', 'colonias.id', '=', 'domicilios.colonia_id')
            ->where('deleted_at', null)->get([
                'personas.id',
                'fecha_registro',
                'folio',
                'nombres',
                'apellido_paterno',
                'apellido_materno',
                'genero',
                'telefono_celular',
                'telefono_fijo',
                'correo',
                'nombre_en_facebook',
                'afiliado',
                'simpatizante',
                'programa',
                'funcion_en_campania',
                'fecha_nacimiento',
                'edadPromedio',
                'observaciones',
                'etiquetas',
                'supervisado',
                'calle',
                'numero_exterior',
                'numero_interior',
                'colonias.nombre as nombreColonia',
                'codigo_postal',
        ]);
        } catch (Exception $e) {
            Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
            return null;
        }

    }
    public function buscar(persona $persona){
        return $persona;
    }
    public function modificar(){

    }

    public function verificar (Request $formulario, persona $persona){
        if(!$persona->supervisado){
            try{
                DB::beginTransaction();
                $persona->supervisado = true;
                $persona->save();
                $user = auth()->user();
                $bitacora = new bitacora();
                $bitacora->accion = 'Persona supervisada : ' . $persona->correo;
                $bitacora->url = url()->current();
                $bitacora->ip = $formulario->ip();
                $bitacora->tipo = 'post';
                $bitacora->user_id = $user->id;
                $bitacora->save();
                DB::commit();
                session()->flash('mensajeExito', 'Se ha supervisado la persona exitosamente');
                return redirect()->route('crudSimpatizantes.index');
            }
            catch(Exception $e){
                DB::rollBack();
                Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
                return back()->withErrors(['errorBorrar' => 'Ha ocurrido un error al supervisar una persona']);
            }
        }
        else{
            return back()->withErrors(['errorBorrar' => 'Ha ocurrido un error al supervisar una persona']);
        }
    }

    public function borrar(Request $formulario, persona $persona){
        if(!isset($persona->deteled_at)){
            try{
                DB::beginTransaction();
                $persona->deleted_at =  Date("Y-m-d H:i:s");
                $persona->save();

                $user = auth()->user();
                $bitacora = new bitacora();
                $bitacora->accion = 'Borrando la persona : ' . $persona->correo;
                $bitacora->url = url()->current();
                $bitacora->ip = $formulario->ip();
                $bitacora->tipo = 'post';
                $bitacora->user_id = $user->id;
                $bitacora->save();

                DB::commit();
                session()->flash('mensajeExito', 'Una persona fue borrada exitosamente');
                return redirect()->route('crudSimpatizantes.index');
            }
            catch(Exception $e){
                DB::rollBack();
                Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
                return back()->withErrors(['errorBorrar' => 'Ha ocurrido un error al borrar una persona']);
            }
        }
        else{
            return back()->withErrors(['errorBorrar' => 'Ha ocurrido un error al borrar una persona']);
        }
    }
}
