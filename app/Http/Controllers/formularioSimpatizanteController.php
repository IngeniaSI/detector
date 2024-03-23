<?php

namespace App\Http\Controllers;

use App\Models\bitacora;
use App\Models\identificacion;
use App\Models\persona;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class formularioSimpatizanteController extends Controller
{
    public function index(){
        return view('formularioSimpatizante');
    }
    public function inicializar(){

    }

    public function agregandoSimpatizante(Request $formulario){
        $formulario->validate([
            'nombre' => 'required',
            'apellido_paterno' => 'required',
            'apellido_materno' => 'required',
            'correo' => 'required|email',
            'genero' => 'required',
            'telefonoFijo' => 'required',
            'telefonoCelular' => 'required',
            'escolaridad' => 'required',
            'claveElectoral' => 'required|regex:/^([A-Z]{6})(\d{8})([B-DF-HJ-NP-TV-Z]{1})(\d{3})$/',
            'curp' => 'required|regex:/^([A-Z]{4})(\d{6})([HM])([A-Z]{5})([0-9A-Z]{2})$/',
        ]);
        try {
            DB::beginTransaction();
            $personaNueva = new persona();
            $personaNueva->nombres = strtoupper($formulario->nombre);
            $personaNueva->apellido_paterno = strtoupper($formulario->apellido_paterno);
            $personaNueva->apellido_materno = strtoupper($formulario->apellido_materno);
            $personaNueva->correo = strtoupper($formulario->correo);
            $personaNueva->genero = strtoupper($formulario->genero);
            $personaNueva->telefono_celular = strtoupper($formulario->telefonoCelular);
            $personaNueva->telefono_fijo = strtoupper($formulario->telefonoFijo);
            $personaNueva->escolaridad = strtoupper($formulario->escolaridad);
            if(isset($formulario->fecha_nacimiento)){
                $personaNueva->fecha_nacimiento = $formulario->fechaNacimiento;
            }
            if(isset($formulario->facebook)){
                $personaNueva->nombre_en_facebook = $formulario->facebook;
            }
            $personaNueva->save();

            $identificacion = new identificacion();
            $identificacion->persona_id = $personaNueva->id;
            $identificacion->curp = strtoupper($formulario->curp);
            $identificacion->clave_elector = strtoupper($formulario->claveElectoral);
            $identificacion->seccion_id = 1;
            $identificacion->save();

            $user = auth()->user();
            $bitacora = new bitacora();
            $bitacora->accion = 'Agregar nueva persona';
            $bitacora->url = url()->current();
            $bitacora->ip = $formulario->ip();
            $bitacora->tipo = 'post';
            $bitacora->user_id = $user->id;
            $bitacora->save();
            DB::commit();
            session()->flash('mensaje', 'Usuario creado con exito');
            return redirect()->route('agregarSimpatizante.index');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
            return back()->withErrors(['errorValidacion' => 'Ha ocurrido un error al registrar al simpatizante']);
        }

    }
}
