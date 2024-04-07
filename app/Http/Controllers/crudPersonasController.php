<?php

namespace App\Http\Controllers;

use App\Models\bitacora;
use App\Models\domicilio;
use App\Models\identificacion;
use App\Models\persona;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class crudPersonasController extends Controller
{
    public function index(persona $persona){
        return view('modificarPersona', ['persona' => $persona->id, 'latitud' => $persona->identificacion->domicilio->latitud,
        'longitud' => $persona->identificacion->domicilio->longitud]);
    }

    public function cargarPersona(persona $persona){
        return [
            'persona' => $persona,
            'identificacion' => $persona->identificacion,
            'domicilio' => $persona->identificacion->domicilio,
            'colonia' => $persona->identificacion->domicilio->colonia,
            'municipio' => isset($persona->identificacion->domicilio->colonia) ? $persona->identificacion->domicilio->colonia->seccionColonia[0]->seccion->distritoLocal->municipio->id : null,
            'entidad' => isset($persona->identificacion->seccion) ? $persona->identificacion->seccion->distritoLocal->municipio->distritoFederal->entidad->id : null,
            'distritoFederal' => isset($persona->identificacion->seccion) ? $persona->identificacion->seccion->distritoLocal->municipio->distritoFederal->id : null,
            'distritoLocal' => isset($persona->identificacion->seccion) ? $persona->identificacion->seccion->distritoLocal->id : null,
            'seccion' => isset($persona->identificacion->seccion) ? $persona->identificacion->seccion->id : null,
        ];
    }

    public function modificarPersona(persona $persona, Request $formulario){
        $formulario->validate([
            'nombre' => 'required',
            'apellido_paterno' => 'required',
            'apellido_materno' => 'required',
            'correo' => 'required|email',
            'genero' => 'required',
            'telefonoCelular' => 'required',
            'escolaridad' => 'required',
            'claveElectoral' => 'nullable|regex:/^([A-Z]{6})(\d{8})([B-DF-HJ-NP-TV-Z]{1})(\d{3})$/',
            'curp' => 'nullable|regex:/^([A-Z]{4})(\d{6})([HM])([A-Z]{5})([0-9A-Z]{2})$/',
            'esAfiliado' => 'required',
            'esSimpatizante' => 'required',
            'programa' => 'required',
            'funciones' => 'required',

            'calle' => 'required',
            'numeroExterior' => 'required',
            'colonia' => 'required|not_in:0',
            'municipio' => 'required|not_in:0',
            'codigoPostal' => 'required|not_in:0',
        ]);
        $coordenadas = explode(',',$formulario->coordenadas);
        try {
            DB::beginTransaction();
            $persona->apellido_paterno = strtoupper($formulario->apellido_paterno);
            $persona->apellido_materno = strtoupper($formulario->apellido_materno);
            $persona->nombres = strtoupper($formulario->nombre);
            $persona->genero = strtoupper($formulario->genero);
            $persona->telefono_celular = strtoupper($formulario->telefonoCelular);
            $persona->correo = strtoupper($formulario->correo);
            $persona->afiliado = strtoupper($formulario->esAfiliado);
            $persona->programa = strtoupper($formulario->programa);
            $persona->simpatizante = strtoupper($formulario->esSimpatizante);
            $persona->funcion_en_campania = strtoupper($formulario->funciones);
            $persona->telefono_fijo = strtoupper($formulario->telefonoFijo);
            $persona->escolaridad = strtoupper($formulario->escolaridad);
            $persona->edadPromedio = $formulario->rangoEdad;
            if(isset($formulario->rolEstructura) && $formulario->rolEstructura != -1){
                if(isset($formulario->rolNumero)){
                    $persona->rolEstructura = $formulario->rolEstructura;
                    $persona->rolNumero = $formulario->rolNumero;
                }
                else{
                    DB::rollBack();
                    switch ($formulario->rolEstructura) {
                        case 'COORDINADOR ESTATAL':
                            return back()->withErrors(['rolNumero' => 'Debe especificar que entidad coordina'])->withInput();
                            break;
                        case 'COORDINADOR DE DISTRITO LOCAL':
                            return back()->withErrors(['rolNumero' => 'Debe especificar que distrito coordina'])->withInput();
                            break;
                        case 'COORDINADOR DE SECCIÓN':
                            return back()->withErrors(['rolNumero' => 'Debe especificar que sección coordina'])->withInput();
                            break;
                        case 'PROMOTOR':
                            return back()->withErrors(['rolNumero' => 'Debe especificar que sección promueve'])->withInput();
                            break;
                    }
                }
            }

            if(isset($formulario->fechaNacimiento)){
                $persona->fecha_nacimiento = $formulario->fechaNacimiento;
            }
            if(isset($formulario->facebook)){
                $persona->nombre_en_facebook = $formulario->facebook;
            }
            if(isset($formulario->fechaRegistro)){
                $persona->fecha_registro = $formulario->fechaRegistro;
            }
            if(isset($formulario->etiquetas)){
                $persona->etiquetas = $formulario->etiquetas;
            }
            if(isset($formulario->observaciones)){
                $persona->observaciones = $formulario->observaciones;
            }
            if(isset($formulario->folio)){
                $persona->folio = $formulario->folio;
            }
            if(isset($formulario->promotor) && $formulario->promotor != -1){
                $persona->persona_id = $formulario->promotor;
            }
            $persona->save();

            //AGREGAR IDENTIFICACION
            $identificacion = identificacion::where('persona_id', $persona->id)
            ->first();
            $identificacion->curp = strtoupper($formulario->curp);
            $identificacion->clave_elector = strtoupper($formulario->claveElectoral);
            if($formulario->seccion > 0){
                $identificacion->seccion_id = $formulario->seccion;
            }
            $identificacion->save();


            //AGREGAR DOMICILIO
            $domicilio = domicilio::where('identificacion_id', $identificacion->id)->first();
            $domicilio->calle = strtoupper($formulario->calle);
            $domicilio->numero_exterior = $formulario->numeroExterior;
            $domicilio->numero_interior = $formulario->numeroInterior;
            $domicilio->colonia_id = $formulario->colonia;
            if(isset($coordenadas) && count($coordenadas) > 1){
                $domicilio->latitud = $coordenadas[0];
                $domicilio->longitud = $coordenadas[1];
            }
            $domicilio->save();

            $user = auth()->user();
            $bitacora = new bitacora();
            $bitacora->accion = 'Se modifico la persona: ' . $persona->id;
            $bitacora->url = url()->current();
            $bitacora->ip = $formulario->ip();
            $bitacora->tipo = 'post';
            $bitacora->user_id = $user->id;
            $bitacora->save();
            DB::commit();
            session()->flash('mensajeExito', 'Usuario modificado con éxito');
            return redirect()->route('crudSimpatizantes.index');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
            return back()->withErrors(['errorValidacion' => 'Ha ocurrido un error al registrar al simpatizante'])->withInput();
        }
    }
}
