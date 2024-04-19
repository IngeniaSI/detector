<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Models\bitacora;
use App\Models\persona;
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
            $draw = ($formulario->get('draw') != null) ? $formulario->get('draw') : 1;
            $start = ($formulario->get('start') != null) ? $formulario->get('start') : 0;
            $length = ($formulario->get('length') != null) ? $formulario->get('length') : 10;
            $filter = $formulario->get('search');
            $search = (isset($filter['value']))? $filter['value'] : false;

            // $user = auth()->user();
                // if($user->getRoleNames()->first() == 'SUPER ADMINISTRADOR' || $user->getRoleNames()->first() == 'ADMINISTRADOR'){
                //     $persona = persona::where('deleted_at', null)
                //     ->orderBy('supervisado', 'DESC')
                //     ->select(
                //         'id',
                //         'folio',
                //         'nombres',
                //         'apellido_paterno',
                //         'apellido_materno',
                //         'telefono_celular',
                //         'supervisado',
                //     )
                //     ->paginate(10);
                // }
                // else{
                //     // $user = auth()->user();
                //     // // return $user;
                //     // $niveles = isset($user->niveles) ? explode( ',', $user->niveles) : null;
                //     // return $niveles; //APLICAR TRIM A CADA NIVEL

                //     $persona = persona::where('deleted_at', null)
                //     ->orderBy('supervisado', 'ASC')
                //     ->select(
                //         'id',
                //         'folio',
                //         'nombres',
                //         'apellido_paterno',
                //         'apellido_materno',
                //         'telefono_celular',
                //         'supervisado',
                //     )
                //     ->paginate(10);
                // }
                // $personas = [];
                // foreach ($persona as $p) {
                //     $aux = [
                //         'personaId' => $p->id,
                //         'folio' => $p->folio,
                //         'nombres' => $p->nombres,
                //         'apellido_paterno' => $p->apellido_paterno,
                //         'apellido_materno' => $p->apellido_materno,
                //         'seccionId' => $p->identificacion->seccion_id,
                //         'telefonoCelular' => $p->telefono_celular,
                //         'distritoLocalId' => isset($p->identificacion->seccion) ? $p->identificacion->seccion->distritoLocal->id : null,
                //         'nombreMunicipio' => isset($p->identificacion->seccion) ? $p->identificacion->seccion->distritoLocal->municipio->id : null,
                //         'distritoFederalId' => isset($p->identificacion->seccion) ? $p->identificacion->seccion->distritoLocal->municipio->distritoFederal->id : null,
                //         'nombreEntidad' => isset($p->identificacion->seccion) ? $p->identificacion->seccion->distritoLocal->municipio->distritoFederal->entidad->id : null,
                //         'supervisado' => $p->supervisado,
                //     ];
                //     array_push($personas, $aux);
                // }
            // return$personas;
            // $pagina = $formulario->input('page', 1);
            $total = persona::where('deleted_at', null)
            ->join('identificacions', 'personas.id', '=', 'identificacions.persona_id')
            ->join('seccions', 'seccions.id', '=', 'identificacions.seccion_id')
            ->count();
            $personas = persona::where('deleted_at', null)
            ->join('identificacions', 'personas.id', '=', 'identificacions.persona_id')
            ->join('seccions', 'seccions.id', '=', 'identificacions.seccion_id')
            ->orderBy('supervisado', 'ASC')
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
