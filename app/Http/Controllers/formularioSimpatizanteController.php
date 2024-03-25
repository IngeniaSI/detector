<?php

namespace App\Http\Controllers;

use App\Models\bitacora;
use App\Models\colonia;
use App\Models\domicilio;
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
            'esAfiliado' => 'required',
            'esSimpatizante' => 'required',
            'programa' => 'required',
            'funciones' => 'required',

            'calle' => 'required',
            'numeroExterior' => 'required',
            'colonia' => 'required',
            'municipio' => 'required',
            'codigoPostal' => 'required',
            'entidadFederativa' => 'required',
            'seccion' => 'required',
            'coordenadas' => 'required',
        ]);
        $coordenadas = explode(',',$formulario->coordenadas);
        try {
            DB::beginTransaction();
            //AGREGAR PERSONA
            $personaNueva = new persona();
            $personaNueva->nombres = strtoupper($formulario->nombre);
            $personaNueva->apellido_paterno = strtoupper($formulario->apellido_paterno);
            $personaNueva->apellido_materno = strtoupper($formulario->apellido_materno);
            $personaNueva->correo = strtoupper($formulario->correo);
            $personaNueva->genero = strtoupper($formulario->genero);
            $personaNueva->telefono_celular = strtoupper($formulario->telefonoCelular);
            $personaNueva->telefono_fijo = strtoupper($formulario->telefonoFijo);
            $personaNueva->escolaridad = strtoupper($formulario->escolaridad);
            $personaNueva->afiliado = $formulario->esAfiliado;
            $personaNueva->programa = $formulario->programa;
            $personaNueva->simpatizante = $formulario->esSimpatizante;
            $personaNueva->funcion_en_campania = $formulario->funciones;
            if(isset($formulario->fechaNacimiento)){
                $personaNueva->fecha_nacimiento = $formulario->fechaNacimiento;
            }
            if(isset($formulario->facebook)){
                $personaNueva->nombre_en_facebook = $formulario->facebook;
            }
            if(isset($formulario->fechaRegistro)){
                $personaNueva->fecha_registro = $formulario->fechaRegistro;
            }
            if(isset($formulario->etiquetas)){
                $personaNueva->etiquetas = $formulario->etiquetas;
            }
            if(isset($formulario->observaciones)){
                $personaNueva->observaciones = $formulario->observaciones;
            }
            if(isset($formulario->folio)){
                $personaNueva->folio = $formulario->folio;
            }
            $personaNueva->save();

            //AGREGAR IDENTIFICACION
            $identificacion = new identificacion();
            $identificacion->persona_id = $personaNueva->id;
            $identificacion->curp = strtoupper($formulario->curp);
            $identificacion->clave_elector = strtoupper($formulario->claveElectoral);
            $identificacion->seccion_id = 1;
            $identificacion->save();

            //AGREGAR COLONIA, TEMPORAL MIENTRAS SALE EL CATALOGO
            $colonia = new colonia();
            $colonia->nombre = strtoupper($formulario->colonia);
            $colonia->tipo = 'TEMPORAL';
            $colonia->codigo_postal = $formulario->codigoPostal;
            $colonia->control = $formulario->seccion; //TEMPORAL
            // $colonia->seccion_id = 1;
            $colonia->save();

            //AGREGAR DOMICILIO
            $domicilio = new domicilio();
            $domicilio->calle = strtoupper($formulario->calle);
            $domicilio->numero_exterior = $formulario->numeroExterior;
            $domicilio->numero_interior = $formulario->numeroInterior;
            $domicilio->colonia_id = $colonia->id;
            $domicilio->identificacion_id = $identificacion->id;
            $domicilio->latitud = $coordenadas[0];
            $domicilio->longitud = $coordenadas[1];
            $domicilio->save();

            $user = auth()->user();
            $bitacora = new bitacora();
            $bitacora->accion = 'Agregar nueva persona';
            $bitacora->url = url()->current();
            $bitacora->ip = $formulario->ip();
            $bitacora->tipo = 'post';
            $bitacora->user_id = $user->id;
            $bitacora->save();
            DB::commit();
            session()->flash('mensajeExito', 'Usuario creado con éxito');
            return redirect()->route('agregarSimpatizante.index');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
            return back()->withErrors(['errorValidacion' => 'Ha ocurrido un error al registrar al simpatizante']);
        }

    }
}
