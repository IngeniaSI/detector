<?php

namespace App\Http\Controllers;

use App\Models\bitacora;
use App\Models\colonia;
use App\Models\distritoFederal;
use App\Models\distritoLocal;
use App\Models\domicilio;
use App\Models\entidad;
use App\Models\identificacion;
use App\Models\municipio;
use App\Models\persona;
use App\Models\seccion;
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
        $colonias = colonia::all();
        $municipios = municipio::all();
        $secciones = seccion::all();
        $entidades = entidad::all();
        $distritosFederales = distritoFederal::all();
        $distritosLocales = distritoLocal::all();

        $promotores = persona::where('rolEstructura', 'PROMOTOR')->get();

        /*
        FUNCIONES
        PROGRAMAS
        */
        return [
            'colonias' => $colonias, 'municipios' => $municipios, 'secciones' => $secciones,
            'entidades' => $entidades, 'distritosFederales' => $distritosFederales,
            'distritosLocales' => $distritosLocales, 'promotores' => $promotores
        ];
    }
    public function filtrarColonias($colonia){
        try{
            $municipios = municipio::orderBy('id')
            ->get();
            if($colonia > 0){
                $coloniaAux = colonia::find($colonia);
                $municipio = $coloniaAux->seccionColonia[0]->seccion->distritoLocal->municipio->id;
            }
            return [
                    'municipio' => $municipio,
                    'colonia' => $colonia,
                    'codigoPostal' => $coloniaAux->codigo_postal,
                    'nombreMunicipio' => municipio::find($municipio)->nombre,
                    'nombreColonia' => $coloniaAux->nombre,
                ];
        }
        catch(Exception $e){
            Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
            return null;
        }

    }

    public function filtrarSecciones($seccion){
        try {
            $entidades = entidad::orderBy('id')
            ->get();
            if($seccion > 0){
                $seccionEncotrada = seccion::find($seccion);
                $entidad = $seccionEncotrada->distritoLocal->municipio->distritoFederal->entidad->id;
                $distritoFederal = $seccionEncotrada->distritoLocal->municipio->distritoFederal->id;
                $distritoLocal = $seccionEncotrada->distritoLocal->id;
            }

            return[
                    'entidad' => $entidad,
                    'distritoFederal' => $distritoFederal,
                    'distritoLocal' => $distritoLocal,
                    'seccion' => $seccion
                ];
        } catch (Exception $e) {
            Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
            return null;
        }
    }

    public function agregandoSimpatizante(Request $formulario){
        session()->flash('validarCamposFormPersona', 'Hay campos erroneos o campos vacios');
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
            'funciones' => 'nullable',

            'calle' => 'required',
            'numeroExterior' => 'required',
            'colonia' => 'required|not_in:0',
            'municipio' => 'required|not_in:0',
            'codigoPostal' => 'required|not_in:0',
        ]);
        $coordenadas = explode(',',$formulario->coordenadas);
        try {
            DB::beginTransaction();
            //AGREGAR PERSONA
            $curpRepetido = identificacion::where('curp', strtoupper($formulario->curp))->first();
            if(!isset($curpRepetido)){
                $personaNueva = new persona();
                $personaNueva->apellido_paterno = strtoupper($formulario->apellido_paterno);
                $personaNueva->apellido_materno = strtoupper($formulario->apellido_materno);
                $personaNueva->nombres = strtoupper($formulario->nombre);
                $personaNueva->genero = strtoupper($formulario->genero);
                $personaNueva->telefono_celular = strtoupper($formulario->telefonoCelular);
                $personaNueva->correo = strtoupper($formulario->correo);
                $personaNueva->afiliado = strtoupper($formulario->esAfiliado);
                $personaNueva->programa = strtoupper($formulario->programa);
                $personaNueva->simpatizante = strtoupper($formulario->esSimpatizante);
                $personaNueva->funcion_en_campania = strtoupper($formulario->funciones);
                $personaNueva->telefono_fijo = strtoupper($formulario->telefonoFijo);
                $personaNueva->escolaridad = strtoupper($formulario->escolaridad);
                $personaNueva->edadPromedio = $formulario->rangoEdad;
                if(isset($formulario->rolEstructura) && $formulario->rolEstructura != -1){
                    if(isset($formulario->rolNumero)){
                        $personaNueva->rolEstructura = $formulario->rolEstructura;
                        $personaNueva->rolNumero = $formulario->rolNumero;
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
                if($formulario->tieneRolTemporal == 'SI'){
                    if(isset($formulario->rolEstructuraTemporal) && $formulario->rolEstructuraTemporal != -1){
                        if(isset($formulario->rolNumeroTemporal)){
                            $personaNueva->rolEstructuraTemporal = $formulario->rolEstructuraTemporal;
                            $personaNueva->rolNumeroTemporal = $formulario->rolNumeroTemporal;
                        }
                        else{
                            DB::rollBack();
                            switch ($formulario->rolEstructuraTemporal) {
                                case 'COORDINADOR ESTATAL':
                                    return back()->withErrors(['rolNumeroTemporal' => 'Debe especificar que entidad coordina'])->withInput();
                                    break;
                                case 'COORDINADOR DE DISTRITO LOCAL':
                                    return back()->withErrors(['rolNumeroTemporal' => 'Debe especificar que distrito coordina'])->withInput();
                                    break;
                                case 'COORDINADOR DE SECCIÓN':
                                    return back()->withErrors(['rolNumeroTemporal' => 'Debe especificar que sección coordina'])->withInput();
                                    break;
                                case 'PROMOTOR':
                                    return back()->withErrors(['rolNumeroTemporal' => 'Debe especificar que sección promueve'])->withInput();
                                    break;
                            }
                        }
                    }
                }

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
                if(isset($formulario->promotor) && $formulario->promotor != -1){
                    $personaNueva->persona_id = $formulario->promotor;
                }
                $personaNueva->save();

                //AGREGAR IDENTIFICACION
                $identificacion = new identificacion();
                $identificacion->persona_id = $personaNueva->id;
                $identificacion->curp = strtoupper($formulario->curp);
                $identificacion->clave_elector = strtoupper($formulario->claveElectoral);
                if($formulario->seccion > 0){
                    $identificacion->seccion_id = $formulario->seccion;
                }
                $identificacion->save();


                //AGREGAR DOMICILIO
                $domicilio = new domicilio();
                $domicilio->calle = strtoupper($formulario->calle);
                $domicilio->numero_exterior = $formulario->numeroExterior;
                $domicilio->numero_interior = $formulario->numeroInterior;
                $domicilio->colonia_id = $formulario->colonia;
                $domicilio->identificacion_id = $identificacion->id;
                if(isset($coordenadas) && count($coordenadas) > 1){
                    $domicilio->latitud = $coordenadas[0];
                    $domicilio->longitud = $coordenadas[1];
                }
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
                session()->forget('validarCamposFormPersona');
                session()->flash('mensajeExito', 'Usuario creado con éxito');
                return redirect()->route('crudSimpatizantes.index');
            }
            else{
                DB::rollBack();
                return back()->withErrors(['curpError' => 'El curp ingresado ya esta registrado'])->withInput();

            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
            return back()->withErrors(['errorValidacion' => 'Ha ocurrido un error al registrar al simpatizante'])->withInput();
        }

    }
}
