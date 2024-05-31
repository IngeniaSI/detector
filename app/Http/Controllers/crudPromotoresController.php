<?php

namespace App\Http\Controllers;

use App\Models\bitacora;
use App\Models\distritoFederal;
use App\Models\distritoLocal;
use App\Models\encuesta;
use App\Models\entidad;
use App\Models\persona;
use App\Models\pregunta;
use App\Models\seccion;
use App\Models\User;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class crudPromotoresController extends Controller
{
    public function index(){
        return view();
    }
    public function cargarPromotores(Request $formulario){
        try {
            $draw = ($formulario->get('draw') != null) ? $formulario->get('draw') : 1;
            $start = ($formulario->get('start') != null) ? $formulario->get('start') : 0;
            $length = ($formulario->get('length') != null) ? $formulario->get('length') : 10;
            $filter = $formulario->get('search');
            $search = (isset($filter['value']))? $filter['value'] : false;



            $personaQuery = persona::where('deleted_at', null)
            ->join('identificacions', 'personas.id', '=', 'identificacions.persona_id')
            ->leftjoin('seccions', 'seccions.id', '=', 'identificacions.seccion_id')
            ->where('rolEstructura', 'PROMOTOR');

            if ($search != false) {
                $personaQuery->where(function($query) use ($search) {
                    $query->where('nombres', 'LIKE', '%' . $search . '%')
                        ->orWhere('telefono_celular', 'LIKE', '%' . $search . '%')
                        ->orWhere('seccion_id', 'LIKE', '%' . $search . '%')
                        ->orWhere('distrito_local_id', 'LIKE', '%' . $search . '%');
                });
            }
            $total = $personaQuery->count();

            $personas = $personaQuery->orderBy('supervisado', 'ASC')->orderBy('id', 'DESC')
                ->select(
                'personas.id',
                DB::raw('IF(apellido_paterno != "", CONCAT(nombres, " ", apellido_paterno), nombres) as nombre_completo'),
            )
            ->skip($start)
            ->take($length)
            ->get();


            $personaQuery->where('supervisado', 0)
            ->count();

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
}
