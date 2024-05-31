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
        try{
            $user = auth()->user();
            switch ($user->nivel_acceso) {
                case 'TODO':
                    //HACER CONSULTA SIN FILTROS
                    $seccionesParaBuscar = seccion::pluck('id')->toArray();

                    break;
                case 'ENTIDAD':
                    //HACER CONSULTA FILTRAR PERSONAS QUE SU IDENTIFICACION
                    //PERTENEZCA A LA LISTA DE SECCIONES PERTENECIENTES A LAS ENTIDADES SELECCIONADAS
                    $nivelesConAcceso = explode(',', $user->niveles);
                    $seccionesParaBuscar = entidad::whereIn('entidads.id', $nivelesConAcceso)
                    ->join('distrito_federals', 'entidads.id', '=','distrito_federals.entidad_id')
                    ->join('municipios', 'distrito_federals.id', '=','municipios.distrito_federal_id')
                    ->join('distrito_locals', 'municipios.id', '=','distrito_locals.municipio_id')
                    ->join('seccions', 'distrito_locals.id', '=','seccions.distrito_local_id')
                    ->pluck('seccions.id')
                    ->toArray();

                    break;
                case 'DISTRITO FEDERAL':
                    //HACER CONSULTA FILTRAR PERSONAS QUE SU IDENTIFICACION
                    //PERTENEZCA A LA LISTA DE SECCIONES PERTENECIENTES A LOS DISTRITOS FEDERALES SELECCIONADAS
                    $nivelesConAcceso = explode(',', $user->niveles);
                    $seccionesParaBuscar = distritoFederal::whereIn('distrito_federals.id', $nivelesConAcceso)
                    ->join('municipios', 'distrito_federals.id', '=','municipios.distrito_federal_id')
                    ->join('distrito_locals', 'municipios.id', '=','distrito_locals.municipio_id')
                    ->join('seccions', 'distrito_locals.id', '=','seccions.distrito_local_id')
                    ->pluck('seccions.id')
                    ->toArray();

                    break;
                case 'DISTRITO LOCAL':
                    //HACER CONSULTA FILTRAR PERSONAS QUE SU IDENTIFICACION
                    //PERTENEZCA A LA LISTA DE SECCIONES PERTENECIENTES A LOS DISTRITOS LOCALES SELECCIONADAS
                    $nivelesConAcceso = explode(',', $user->niveles);
                    $seccionesParaBuscar = distritoLocal::whereIn('distrito_locals.id', $nivelesConAcceso)
                    ->join('seccions', 'distrito_locals.id', '=','seccions.distrito_local_id')
                    ->pluck('seccions.id')
                    ->toArray();

                    break;
                case 'SECCION':
                    //HACER CONSULTA FILTRAR PERSONAS QUE SU IDENTIFICACION
                    //PERTENEZCA A LA LISTA DE SECCIONES PERTENECIENTES A LAS SECCIONES SELECCIONADAS
                    $seccionesParaBuscar = explode(',', $user->niveles);
                    $seccionesParaBuscar = array_map('intval', $seccionesParaBuscar);

                    break;
            }

            $colonias = colonia::join('seccion_colonias', 'colonias.id', '=', 'seccion_colonias.colonia_id')
            ->join('seccions', 'seccions.id', '=', 'seccion_colonias.seccion_id')
            ->join('distrito_locals', 'distrito_locals.id', '=', 'seccions.distrito_local_id')
            ->join('municipios', 'distrito_locals.municipio_id', '=', 'municipios.id')
            ->select('colonias.id', 'colonias.nombre', 'municipios.nombre as nombreMunicipio')
            ->distinct()
            ->whereIn('seccion_colonias.seccion_id', $seccionesParaBuscar)
            ->get();

            //FILTRADO DE COLONIAS PARA ENCONTRAR REPETIDOS Y CONCATENAR NOMBRE MUNICIPIO
            $colección = collect($colonias);
            $grupos = $colección->groupBy('nombre');
            $nombresRepetidos = $grupos->filter(function ($grupo) {
                return $grupo->count() > 1;
            });
            $nombresRepetidos->each(function ($grupo) {
                $grupo->transform(function ($item) {
                    $item['nombre'] .= ', ' . $item['nombreMunicipio'];
                    return $item;
                });
            });
            $secciones = seccion::whereIn('id', $seccionesParaBuscar)
            ->distinct()
            ->orderBy('seccions.id', 'ASC')
            ->get(['seccions.id']);

            $distritosLocales = distritoLocal::join('seccions', 'seccions.distrito_local_id', '=', 'distrito_locals.id')
            ->whereIn('seccions.id', $seccionesParaBuscar)
            ->distinct()
            ->orderBy('distrito_locals.id', 'ASC')
            ->get(['distrito_locals.id']);

            $distritosFederales = distritoFederal::join('municipios', 'distrito_federals.id', '=', 'municipios.distrito_federal_id')
            ->join('distrito_locals', 'distrito_locals.municipio_id', '=', 'municipios.id')
            ->join('seccions', 'seccions.distrito_local_id', '=', 'distrito_locals.id')
            ->whereIn('seccions.id', $seccionesParaBuscar)
            ->distinct()
            ->orderBy('distrito_federals.id', 'ASC')
            ->get(['distrito_federals.id']);

            $municipios = municipio::join('distrito_locals', 'distrito_locals.municipio_id', '=', 'municipios.id')
            ->join('seccions', 'seccions.distrito_local_id', '=', 'distrito_locals.id')
            ->whereIn('seccions.id', $seccionesParaBuscar)
            ->distinct()
            ->orderBy('municipios.id', 'ASC')
            ->get(['municipios.id', 'municipios.nombre']);

            $entidades = entidad::join('distrito_federals', 'distrito_federals.entidad_id', '=', 'entidads.id')
            ->join('municipios', 'distrito_federals.id', '=', 'municipios.distrito_federal_id')
            ->join('distrito_locals', 'distrito_locals.municipio_id', '=', 'municipios.id')
            ->join('seccions', 'seccions.distrito_local_id', '=', 'distrito_locals.id')
            ->whereIn('seccions.id', $seccionesParaBuscar)
            ->distinct()
            ->orderBy('entidads.id', 'ASC')
            ->get(['entidads.id', 'entidads.nombre']);

            $promotores = persona::where('rolEstructura', 'PROMOTOR')->get();

            /*
            PROGRAMAS
            */
            return [
                'colonias' => $colonias, 'municipios' => $municipios, 'secciones' => $secciones,
                'entidades' => $entidades, 'distritosFederales' => $distritosFederales,
                'distritosLocales' => $distritosLocales, 'promotores' => $promotores
            ];
        }
        catch(Exception $e){
            Log::info($e->getLine(). ' | ' . $e->getMessage());
            return null;
        }
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
            'apellido_materno' => 'nullable',
            'correo' => 'nullable|email',
            'genero' => 'nullable',
            'telefonoCelular' => 'nullable',
            'escolaridad' => 'nullable',
            'claveElectoral' => 'nullable|regex:/^([A-Z]{6})(\d{8})([B-DF-HJ-NP-TV-Z]{1})(\d{3})$/',
            'curp' => 'nullable|regex:/^([A-Z]{4})(\d{6})([HM])([A-Z]{5})([0-9A-Z]{2})$/',
            'esAfiliado' => 'nullable',
            'esSimpatizante' => 'nullable',
            'programa' => 'nullable',
            'funciones' => 'nullable',

            'calle' => 'nullable',
            'numeroExterior' => 'nullable',
        ]);
        $coordenadas = explode(',',$formulario->coordenadas);
        try {
            DB::beginTransaction();
            //AGREGAR PERSONA
            $curpRepetido = identificacion::where('curp', strtoupper($formulario->curp))->first();
            if(!isset($formulario->curp) || !isset($curpRepetido)){
                $personaNoHomogama = persona::join('identificacions', 'personas.id', '=', 'identificacions.persona_id')
                ->join('domicilios', 'identificacions.id', '=', 'domicilios.identificacion_id')
                ->where('nombres', strtoupper($formulario->nombre))
                ->where('apellido_paterno', strtoupper($formulario->apellido_paterno))
                ->where(function($query) use ($formulario) {
                    $query->where('telefono_celular', $formulario->telefonoCelular)
                    ->orWhere('correo', $formulario->correo)
                    ->orWhere('calle', $formulario->calle);
                })
                ->first();
                if(!isset($personaNoHomogama)){
                    $user = auth()->user();
                    $personaNueva = new persona();
                    $personaNueva->user_id = $user->id;
                    $personaNueva->apellido_paterno = strtoupper($formulario->apellido_paterno);
                    $personaNueva->apellido_materno = strtoupper($formulario->apellido_materno);
                    $personaNueva->nombres = strtoupper($formulario->nombre);
                    $personaNueva->tipoRegistro = $formulario->tipoRegistro;
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
                        else if($formulario->rolEstructura == 'PROMOTOR'){
                            $personaNueva->rolEstructura = $formulario->rolEstructura;
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
                            }
                        }
                    }
                    if($formulario->tieneRolTemporal == 'SI'){
                        if(isset($formulario->rolEstructuraTemporal) && $formulario->rolEstructuraTemporal != -1){
                            if(isset($formulario->rolNumeroTemporal)){
                                $personaNueva->rolEstructuraTemporal = $formulario->rolEstructuraTemporal;
                                $personaNueva->rolNumeroTemporal = $formulario->rolNumeroTemporal;
                            }
                            else if($formulario->rolEstructuraTemporal == 'PROMOTOR'){
                                $personaNueva->rolEstructuraTemporal = $formulario->rolEstructuraTemporal;
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
                    if(isset($formulario->promotor) && $formulario->promotor > 0){
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
                    if($formulario->colonia > 0){
                        $domicilio->colonia_id = $formulario->colonia;
                    }
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
                    session()->flash('mensajeExito', 'La persona se ha creado con éxito');
                    return redirect()->route('crudSimpatizantes.index');
                }
                else{
                    DB::rollBack();
                    return back()->withErrors(['errorValidacion' => 'El registro realizado ya se encuentra registrado. Revise el registro existente
                    o verifique los campos de identificación (Nombre, Apellido paterno y dato de contacto)'])->withInput();

                }
            }
            else{
                DB::rollBack();
                return back()->withErrors(['errorValidacion' => 'El curp ingresado ya esta registrado'])->withInput();

            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
            return back()->withErrors(['errorValidacion' => 'Ha ocurrido un error al registrar al simpatizante'])->withInput();
        }

    }
}
