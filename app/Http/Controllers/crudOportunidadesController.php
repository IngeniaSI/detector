<?php

namespace App\Http\Controllers;

use App\Exports\oportunidadesExport;
use App\Models\bitacora;
use App\Models\distritoFederal;
use App\Models\distritoLocal;
use App\Models\encuesta;
use App\Models\entidad;
use App\Models\objetivo;
use App\Models\oportunidad;
use App\Models\persona;
use App\Models\pregunta;
use App\Models\seccion;
use App\Models\seguimiento;
use App\Models\User;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;

class crudOportunidadesController extends Controller
{
    public function index(){
        return view('crudOportunidades');
    }
    public function cargarOportunidades(Request $formulario){
        try {
            $draw = ($formulario->get('draw') != null) ? $formulario->get('draw') : 1;
            $start = ($formulario->get('start') != null) ? $formulario->get('start') : 0;
            $length = ($formulario->get('length') != null) ? $formulario->get('length') : 10;
            $filter = $formulario->get('search');
            $search = (isset($filter['value']))? $filter['value'] : false;


            $encuestasQuery = oportunidad::where('oportunidads.deleted_at', null)
            ->join('objetivos', 'oportunidads.objetivo_id', '=', 'objetivos.id')
            ->join('personas', 'personas.id', '=', 'oportunidads.persona_id');
            if ($search != false) {
                $encuestasQuery->where(function($query) use ($search) {
                    $query->where('nombre', 'LIKE', '%' . $search . '%');
                });
            }
            $total = $encuestasQuery->count();

            $encuestas = $encuestasQuery->select(
                'oportunidads.id',
                'nombre',
                DB::raw('IF(apellido_paterno != "", CONCAT(nombres, " ", apellido_paterno), nombres) as nombre_completo'),
                'estatus',
            )
            ->orderBy('id', 'DESC')
            ->skip($start)
            ->take($length)
            ->get();

            return [
                'data' => $encuestas,
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

    public function inicializar(Request $formulario){
        return [
            'objetivos' => objetivo::all(),
            'personas' => persona::where('deleted_at', null)->get(),
            'promotores' => persona::where('deleted_at', null)->where('rolEstructura', 'PROMOTOR')
            ->orWhere('rolEstructuraTemporal', 'PROMOTOR')->get()
        ];
    }

    public function agregar(Request $formulario){
        session()->flash('encuestaCrearErrores', true);
        try{
            $user = auth()->user();
            DB::beginTransaction();
            $nuevaOportunidad = new oportunidad();
            $nuevaOportunidad->promotor_id = $formulario->promotorVinculado;
            $nuevaOportunidad->objetivo_id = $formulario->oportunidadVinculada;
            $nuevaOportunidad->persona_id = $formulario->personaVinculada;
            $nuevaOportunidad->estatus = 'PENDIENTE';
            $nuevaOportunidad->save();
            DB::commit();
            session()->forget('encuestaCrearErrores');
            session()->flash('mensajeExito', 'La oportunidad se ha creada con éxito');
            return redirect()->route('oportunidades.index');
        }
        catch(Exception $e){
            DB::rollBack();
            Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
            return back()->withErrors(['errorValidacion' => 'Ha ocurrido un error al crear una encuesta'])->withInput();
        }
    }

    public function cambiarEstado(Request $formulario){
        // session()->flash('encuestaCrearErrores', true);
        try{
            $user = auth()->user();
            DB::beginTransaction();
            $oportunidad = oportunidad::find($formulario->idOportunidad);
            $oportunidad->estatus = $formulario->estatusNuevo;
            $oportunidad->save();

            DB::commit();
            // session()->forget('encuestaCrearErrores');
            return true;
        }
        catch(Exception $e){
            DB::rollBack();
            Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
            return false;
        }
    }

    public function agregarActividad(oportunidad $oportunidad, Request $formulario){
        // session()->flash('encuestaCrearErrores', true);
        try{
            $user = auth()->user();
            DB::beginTransaction();
            $nuevoSeguimiento = new seguimiento();
            $nuevoSeguimiento->oportunidad_id = $oportunidad->id;
            $nuevoSeguimiento->accion = $formulario->actividadRealizada;
            $nuevoSeguimiento->observacion = $formulario->respuestaPersona;
            $nuevoSeguimiento->fecha_registro = $formulario->fechaRegistro;
            $nuevoSeguimiento->hora_registro = $formulario->horaActividad;
            $nuevoSeguimiento->save();

            if($oportunidad->estatus == 'PENDIENTE'){
                $oportunidad->estatus = 'INICIADO';

                $oportunidad->save();
            }
            DB::commit();
            // session()->forget('encuestaCrearErrores');
            session()->flash('mensajeExito', 'Se ha registrado la actividad con éxito');
            return redirect()->route('oportunidades.index');
        }
        catch(Exception $e){
            DB::rollBack();
            Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
            return back()->withErrors(['errorValidacion' => 'Ha ocurrido un error al crear una encuesta'])->withInput();
        }
    }

    public function obtenerSeguimiento(oportunidad $oportunidad){
        $seguimientos = seguimiento::where('oportunidad_id', $oportunidad->id)->orderBy('created_at', 'DESC')->get();
        $persona = persona::find($oportunidad->persona_id);
        $objetivo = objetivo::find($oportunidad->objetivo_id);
        return [
            'seguimientos' => $seguimientos,
            'nombreOportunidad' => $objetivo->nombre,
            'nombrePersona' => $persona->nombres . ' ' . $persona->apellido_paterno . ' ' . $persona->apellido_materno
        ];
    }

    public function exportarParaPromotor(Request $formulario){
        $promotor = persona::find($formulario->idPromotor);
        $estatus = explode(',', $formulario->estatusSeleccionado);

        if(isset($promotor)){
            if($formulario->estatusSeleccionado == null){
                $estatus = array('INICIADO', 'PENDIENTE', 'COMPROMISO', 'CUMPLIDO', 'PERDIDO');
            }
            return Excel::download(
                new oportunidadesExport($promotor->id, $estatus),
            'Seguimiento Día Del ' . $promotor->nombres . ' ' . $promotor->apellido_paterno . '_' . $promotor->id
            . '.xlsx');
        }
    }
}
