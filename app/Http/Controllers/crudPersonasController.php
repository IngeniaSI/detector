<?php

namespace App\Http\Controllers;

use App\Exports\listadoPersonasExport;
use App\Exports\personasMultipleExport;
use App\Models\bitacora;
use App\Models\distritoFederal;
use App\Models\distritoLocal;
use App\Models\domicilio;
use App\Models\identificacion;
use App\Models\persona;
use App\Models\seccion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class crudPersonasController extends Controller
{
    public function index(persona $persona){
        if(auth()->user()->getRoleNames()->first() == 'CAPTURISTA' && $persona->supervisado){
            session()->flash('personaModificarDenegada', 'No se puede modificar una persona autorizada');
            return redirect()->route('crudSimpatizantes.index');
        }
        $distritosEstatales = distritoFederal::select(
            'id',
            'id as text',
        )
        ->get();
        $distritoLocales = distritoLocal::select(
            'id',
            'id as text',
        )
        ->get();
        $secciones = seccion::select(
            'id',
            'id as text',
        )
        ->get();

        return view('formularioSimpatizante', ['persona' => $persona->id, 'latitud' => $persona->identificacion->domicilio->latitud,
        'longitud' => $persona->identificacion->domicilio->longitud, 'distritosEstatales' => $distritosEstatales,
        'distritoLocales' => $distritoLocales, 'secciones' => $secciones]);
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
        session()->flash('validarCamposFormPersona', 'Hay campos erroneos o campos vacios');
        session()->flash('noEsCargaInicial', true);
        $formulario->validate([
            'nombre' => 'required',
            'apellido_paterno' => 'required',
            'apellido_materno' => 'nullable',
            'correo' => 'nullable|email',
            'genero' => 'nullable',
            'escolaridad' => 'nullable',
            'claveElectoral' => 'nullable|regex:/^([A-Z]{6})(\d{8})([B-DF-HJ-NP-TV-Z]{1})(\d{3})$/',
            'curp' => 'nullable|regex:/^([A-Z]{4})(\d{6})([HM])([A-Z]{5})([0-9A-Z]{2})$/',
            'esAfiliado' => 'nullable',
            'esSimpatizante' => 'nullable',
            'programa' => 'nullable',
            'funciones' => 'nullable',

            'calle' => 'nullable|string',
            'numeroExterior' => 'nullable|string',
            'colonia' => 'nullable',
            'telefonoCelular' => [
                'nullable',
                Rule::requiredIf(function () use ($formulario) {
                    // Si no hay dirección completa, el celular es obligatorio
                    $direccionCompleta =
                        !empty($formulario->calle) &&
                        !empty($formulario->numeroExterior) &&
                        !empty($formulario->colonia) &&
                        $formulario->colonia != 0;

                    return !$direccionCompleta;
                }),
            ],
        ],
        [
            'telefonoCelular.required' => 'Debes ingresar un número celular o completar todos los datos de dirección.',
            'direccion_incompleta' => 'Si ingresas parte de la dirección, debes completarla.',
        ]);
        // Validación lógica adicional manual (para marcar dirección incompleta)
        if (
            ($formulario->filled('calle') || $formulario->filled('numeroExterior') || $formulario->colonia != 0) &&
            (empty($formulario->calle) || empty($formulario->numeroExterior) || $formulario->colonia == 0)
        ) {
            return back()->withErrors([
                'direccion_incompleta' => 'Si ingresas parte de la dirección, debes completarla.',
            ])->withInput();
        }
        $coordenadas = explode(',',$formulario->coordenadas);
        $curpRepetido = identificacion::where('curp', strtoupper($formulario->curp))->first();
            if(!isset($formulario->curp) || !isset($curpRepetido)){
                try {
                    DB::beginTransaction();
                    $persona->apellido_paterno = strtoupper($formulario->apellido_paterno);
                    $persona->apellido_materno = strtoupper($formulario->apellido_materno);
                    $persona->nombres = strtoupper($formulario->nombre);
                    $persona->tipoRegistro = $formulario->tipoRegistro;
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
                        else if($formulario->rolEstructura == 'PROMOTOR'){
                            $persona->rolEstructura = $formulario->rolEstructura;
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
                                $persona->rolEstructuraTemporal = $formulario->rolEstructuraTemporal;
                                $persona->rolNumeroTemporal = $formulario->rolNumeroTemporal;
                            }
                            else if($formulario->rolEstructuraTemporal == 'PROMOTOR'){
                                $persona->rolEstructuraTemporal = $formulario->rolEstructuraTemporal;
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
                    if(isset($formulario->promotor) && $formulario->promotor > 0){
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
                    if($formulario->colonia > 0){
                        $domicilio->colonia_id = $formulario->colonia;
                    }
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
                    session()->forget('validarCamposFormPersona');
                    session()->forget('noEsCargaInicial');
                    session()->flash('mensajeExito', 'La persona se ha modificado con éxito');
                    return redirect()->route('crudSimpatizantes.index');
                } catch (Exception $e) {
                    DB::rollBack();
                    Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
                    return back()->withErrors(['errorValidacion' => 'Ha ocurrido un error al registrar al simpatizante'])->withInput();
                }
            }
            else{
                DB::rollBack();
                return back()->withErrors(['curp' => 'El curp ingresado ya esta registrado'])->withInput();
            }

    }
    public function consultar(persona $persona){
        $datos = [
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
        return view('consultarSimpatizante', $datos);
    }
    public function importarPersonas(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,csv,xls',
        ]);

        $data = Excel::toArray([], $request->file('archivo'))[0];

        if (count($data) < 2) {
            return back()->with('error', 'El archivo está vacío o no tiene datos.');
        }

        $headers = array_map('trim', $data[0]);

        // Mapeo de columnas requeridas
        $required = [
            'Consecutivo',
            'Nombres',
            'Apellido paterno',
            'Apellido materno',
        ];

        foreach ($required as $col) {
            if (!in_array($col, $headers)) {
                return back()->with('error', "Falta la columna requerida: {$col}");
            }
        }

        // Índices de columnas
        $idx = array_flip($headers);

        DB::beginTransaction();

        try {
            for ($i = 1; $i < count($data); $i++) {
                $fila = $data[$i];

                $id = trim($fila[$idx['Consecutivo']] ?? null);
                if (empty($id)) continue; // si no hay consecutivo, se omite

                // Buscar si existe la persona
                $persona = persona::find($id);

                // Datos de persona
                $personaData = [
                    'folio' => $fila[$idx['Folio']] ?? null,
                    'promotor_id' => $fila[$idx['promotor_id']] ?? null,
                    'nombres' => $fila[$idx['Nombres']] ?? null,
                    'apellido_paterno' => $fila[$idx['Apellido paterno']] ?? null,
                    'apellido_materno' => $fila[$idx['Apellido materno']] ?? null,
                    'genero' => $fila[$idx['Genero']] ?? null,
                    'fecha_nacimiento' => $fila[$idx['Fecha de nacimiento']] ?? null,
                    'edadPromedio' => $fila[$idx['Rango de edad']] ?? null,
                    'telefono_celular' => $fila[$idx['Telefono celular']] ?? null,
                    'telefono_fijo' => $fila[$idx['Telefono fijo']] ?? null,
                    'correo' => $fila[$idx['Correo']] ?? null,
                    'facebook' => $fila[$idx['Facebook']] ?? null,
                    'escolaridad' => $fila[$idx['Escolaridad']] ?? null,
                    'afiliado' => $fila[$idx['Afiliado']] ?? null,
                    'simpatizante' => $fila[$idx['Simpatizante']] ?? null,
                    'programa' => $fila[$idx['Programa']] ?? null,
                    'funcion_en_campania' => $fila[$idx['Función en campaña']] ?? null,
                    'rolEstructura' => $fila[$idx['Rol Designado']] ?? null,
                    'rolNumero' => $fila[$idx['Id del rol']] ?? null,
                    'supervisado' => $fila[$idx['Supervisado']] ?? null,
                    'observaciones' => $fila[$idx['Observaciones']] ?? null,
                    'etiquetas' => $fila[$idx['Etiquetas']] ?? null,
                ];

                // Si la persona no existe, crearla
                if (!$persona) {
                    $persona = persona::create(array_merge($personaData, ['id' => $id]));
                } else {
                    // Si existe, actualizar
                    $persona->update(array_filter($personaData, fn($v) => $v !== null && $v !== ''));
                }

                // Datos de identificación
                $identificacionData = [
                    'clave_electoral' => $fila[$idx['Clave electoral']] ?? null,
                    'curp' => $fila[$idx['CURP']] ?? null,
                    'seccion_id' => $fila[$idx['Sección']] ?? null,
                    // 'distrito_local' => $fila[$idx['Distrito Local']] ?? null,
                    // 'municipio' => $fila[$idx['Municipio']] ?? null,
                    // 'distrito_federal' => $fila[$idx['Distrito Federal']] ?? null,
                    // 'entidad_federativa' => $fila[$idx['Entidad Federativa']] ?? null,
                    'persona_id' => $persona->id,
                ];

                $identificacion = identificacion::firstOrNew(['persona_id' => $persona->id]);
                $identificacion->fill(array_filter($identificacionData, fn($v) => $v !== null && $v !== ''));
                $identificacion->save();

                // Datos de domicilio
                $domicilioData = [
                    'calle' => $fila[$idx['Calle']] ?? null,
                    'numero_exterior' => $fila[$idx['Numero exterior']] ?? null,
                    'numero_interior' => $fila[$idx['Numero interior']] ?? null,
                    'codigo_postal' => $fila[$idx['Código postal']] ?? null,
                    'colonia' => $fila[$idx['Colonia']] ?? null,
                    'identificacion_id' => $identificacion->id,
                ];

                // Buscar domicilio existente o crear nuevo
                $domicilio = domicilio::firstOrNew(['identificacion_id' => $identificacion->id]);
                $domicilio->fill(array_filter($domicilioData, fn($v) => $v !== null && $v !== ''));
                $domicilio->save();




            }

            DB::commit();

            return back()->with('success', 'Importación completada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e);
            return back()->with('error', 'Error al importar: ' . $e->getMessage());
        }
    }


    public function exportarPersonas(){
        $fechaInicio = '2000-01-01';
        $fechaFin = '2999-12-31';

        return Excel::download(new personasMultipleExport($fechaInicio, $fechaFin), 'listado_completo.xlsx');

    }
}
