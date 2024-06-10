<?php

namespace App\Http\Controllers;

use App\Models\objetivo;
use App\Models\oportunidad;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class crudObjetivoController extends Controller
{
    public function index(){
        return view('crudObjetivos');
    }

    public function inicializar(Request $formulario){

    }

    public function cargarTabla(Request $formulario){
        try {
            $draw = ($formulario->get('draw') != null) ? $formulario->get('draw') : 1;
            $start = ($formulario->get('start') != null) ? $formulario->get('start') : 0;
            $length = ($formulario->get('length') != null) ? $formulario->get('length') : 10;
            $filter = $formulario->get('search');
            $search = (isset($filter['value']))? $filter['value'] : false;


            $encuestasQuery = objetivo::where('deleted_at', null);
            if ($search != false) {
                $encuestasQuery->where(function($query) use ($search) {
                    $query->where('nombre', 'LIKE', '%' . $search . '%');
                });
            }
            $total = $encuestasQuery->count();

            $encuestas = $encuestasQuery->select(
                'id',
                'nombre',
                'descripcion',
                'numeroPasos',
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

    public function agregar(Request $formulario){
        session()->flash('encuestaCrearErrores', true);
        try{
            DB::beginTransaction();
            $nuevoObjetivo = new objetivo();
            $nuevoObjetivo->nombre = $formulario->nombreObjetivo;
            $nuevoObjetivo->descripcion = $formulario->descripcionObjetivo;
            $nuevoObjetivo->numeroPasos = count(explode(',', $formulario->etapasObjetivo));
            $nuevoObjetivo->arrayPasos = $formulario->etapasObjetivo;
            $nuevoObjetivo->save();
            DB::commit();
            session()->forget('encuestaCrearErrores');
            session()->flash('mensajeExito', 'El objetivo se ha creado con éxito');
            return redirect()->route('objetivos.index');
        }
        catch(Exception $e){
            DB::rollBack();
            Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
            return back()->withErrors(['errorValidacion' => 'Ha ocurrido un error al crear un objetivo'])->withInput();
        }
    }

    public function editar(Request $formulario, objetivo $objetivo){
        session()->flash('encuestaCrearErrores', true);
        try{
            DB::beginTransaction();
            $objetivo->nombre = $formulario->nombreObjetivo;
            $objetivo->descripcion = $formulario->descripcionObjetivo;
            $objetivo->numeroPasos = count(explode(',', $formulario->etapasObjetivo));
            $objetivo->arrayPasos = $formulario->etapasObjetivo;
            $objetivo->save();
            DB::commit();
            session()->forget('encuestaCrearErrores');
            session()->flash('mensajeExito', 'El objetivo se ha modificado con éxito');
            return redirect()->route('objetivos.index');
        }
        catch(Exception $e){
            DB::rollBack();
            Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
            return back()->withErrors(['errorValidacion' => 'Ha ocurrido un error al modificar un objetivo'])->withInput();
        }
    }
    public function borrar(){
        return 'WIP';
    }

    public function cargar(objetivo $objetivo){
        return $objetivo;
    }

    public function cambiarEstatus(objetivo $objetivo){
            try{
                DB::beginTransaction();
                if($objetivo->estatus == 'DESACTIVADO'){
                    $objetivo->estatus = "ACTIVADO";
                    $objetivo->save();
                    session()->flash('mensajeExito', 'El objetivo ' . $objetivo->nombre . ' ha sido activado.');
                }
                else{
                    $objetivo->estatus = "DESACTIVADO";
                    $objetivo->save();
                    session()->flash('mensajeExito', 'El objetivo ' . $objetivo->nombre . ' ha sido desactivado.');
                }
                DB::commit();
                return redirect()->route('objetivos.index');
            }
            catch(Exception $e){
                DB::rollBack();
                return back()->withErrors(['errorValidacion' => 'Ha ocurrido un error al registrar el usuario'])->withInput();
            }

    }
}
