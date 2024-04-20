<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Models\bitacora;
use App\Models\distritoFederal;
use App\Models\distritoLocal;
use App\Models\entidad;
use App\Models\persona;
use App\Models\seccion;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class tablaSimpatizantesController extends Controller
{
    public function index(){
        return view('tablaSimpatizantes');
    }
    public function inicializar(Request $formulario){
        try {
            //CONTROLADOR DE NIVELES DE ACCESO
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


            $user = auth()->user();
            $draw = ($formulario->get('draw') != null) ? $formulario->get('draw') : 1;
            $start = ($formulario->get('start') != null) ? $formulario->get('start') : 0;
            $length = ($formulario->get('length') != null) ? $formulario->get('length') : 10;
            $filter = $formulario->get('search');
            $search = (isset($filter['value']))? $filter['value'] : false;



            $personaQuery = persona::where('deleted_at', null)
            ->join('identificacions', 'personas.id', '=', 'identificacions.persona_id')
            ->leftjoin('seccions', 'seccions.id', '=', 'identificacions.seccion_id');

            if($user->nivel_acceso != 'TODO'){
                $personaQuery->whereIn('seccion_id', $seccionesParaBuscar);
            }
            if ($search != false) {
                $personaQuery->where(function($query) use ($search) {
                    $query->where('nombres', 'LIKE', '%' . $search . '%')
                        ->orWhere('seccion_id', 'LIKE', '%' . $search . '%')
                        ->orWhere('distrito_local_id', 'LIKE', '%' . $search . '%');
                });
            }
            $total = $personaQuery->count();

            $personas = $personaQuery->orderBy('supervisado', 'ASC')
            ->select(
                'personas.id',
                DB::raw('CONCAT(nombres, " ", apellido_paterno) as nombre_completo'),
                'telefono_celular',
                'seccions.id as seccionId',
                'seccions.distrito_local_id as distritoLocalId',
                'supervisado',
            )
            ->skip($start)
            ->take($length)
            ->get();

            return [
                'data' => $personas,
                'length' => $length,
                'recordsTotal' => $total,
                'recordsFiltered' => $total,
                'start' => $start,
                'draw' => $draw,
            ];

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

    public function ver(persona $persona){
        return [
            'fechaRegistro' => $persona->fecha_registro,
            'folio' => $persona->folio,
            'promotor' => (isset($persona->promotor)) ? $persona->promotor->nombres . ' ' . $persona->promotor->apellido_paterno . ' ' . $persona->promotor->apellido_materno : '',
            'nombreCompleto' => $persona->nombres . ' ' . $persona->apellido_paterno . ' ' . $persona->apellido_materno,
            'genero' => $persona->genero,
            'fechaNacimiento' => $persona->fecha_nacimiento,
            'rangoEdad' => $persona->edadPromedio,
            'escolaridad' => $persona->escolaridad,
            'telefonoCelular' => $persona->telefono_celular,
            'telefonoFijo' => $persona->telefono_fijo,
            'correo' => $persona->correo,
            'facebook' => $persona->nombre_en_facebook,
            'calle' => $persona->identificacion->domicilio->calle,
            'numeroExterior' => $persona->identificacion->domicilio->numero_exterior,
            'numeroInterior' => $persona->identificacion->domicilio->numero_interior,
            'codigoPostal' => $persona->identificacion->domicilio->colonia->codigo_postal,
            'municipio' => (isset($persona->identificacion->seccion)) ? $persona->identificacion->seccion->distritoLocal->municipio->distritoFederal->entidad->nombre : '',
            'colonia' => $persona->identificacion->domicilio->colonia->nombre,
            'latitud' => $persona->identificacion->domicilio->latitud,
            'longitud' => $persona->identificacion->domicilio->longitud,
            'claveElectoral' => $persona->identificacion->clave_elector,
            'curp' => $persona->identificacion->curp,
            'seccion' => (isset($persona->identificacion->seccion)) ? $persona->identificacion->seccion->id : '',
            'distritoLocal' => (isset($persona->identificacion->seccion)) ? $persona->identificacion->seccion->distritoLocal->id : '',
            'municipio' => (isset($persona->identificacion->seccion)) ? $persona->identificacion->seccion->distritoLocal->municipio->nombre : '',
            'distritoFederal' => (isset($persona->identificacion->seccion)) ? $persona->identificacion->seccion->distritoLocal->municipio->distritoFederal->id : '',
            'entidadFederativa' => (isset($persona->identificacion->seccion)) ? $persona->identificacion->seccion->distritoLocal->municipio->distritoFederal->entidad->nombre : '',
            'afiliado' => $persona->afiliado,
            'simpatizante' => $persona->simpatizante,
            'programa' => $persona->programa,
            'rolEstructura' => $persona->rolEstructura,
            'rolNumerico' => $persona->rolNumero,
            'funcionAsignada' => $persona->funcion_en_campania,
            'etiquetas' => $persona->etiquetas,
            'observaciones' => $persona->observaciones,
        ];
    }

    public function verificar (Request $formulario, persona $persona){
        try{
            DB::beginTransaction();
            $user = auth()->user();
            $bitacora = new bitacora();
            if(!$persona->supervisado){
                $persona->supervisado = true;
                $persona->save();
                $bitacora->accion = 'Persona cambio a supervisada : ' . $persona->correo;
            }
            else{
                $persona->supervisado = false;
                $persona->save();
                $bitacora->accion = 'Persona cambio a no supervisada : ' . $persona->correo;
            }
                $bitacora->url = url()->current();
                $bitacora->ip = $formulario->ip();
                $bitacora->tipo = 'post';
                $bitacora->user_id = $user->id;
                $bitacora->save();
                DB::commit();
                session()->flash('mensajeExito', 'Se ha cambiado el estado de la persona exitosamente');
                return redirect()->route('crudSimpatizantes.index');
            }
            catch(Exception $e){
                DB::rollBack();
                Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
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

    public function descargar(){
        $fechaActual = Carbon::now()->format('d-F');
        $user = auth()->user();
        if($user->getRoleNames()->first() == 'SUPER ADMINISTRADOR' || $user->getRoleNames()->first() == 'ADMINISTRADOR'){
            return Excel::download(new UsersExport, 'personas-' . $fechaActual . '.xlsx');
        }
        else{
            return Excel::download(new UsersExport, 'personas-' . $fechaActual . '.xlsx');
        }
    }
}
