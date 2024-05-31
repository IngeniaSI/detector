<?php

namespace App\Http\Controllers;

use App\Exports\resultadoExport;
use App\Models\encuesta;
use App\Models\persona;
use App\Models\respuesta;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class crudResultadosController extends Controller
{
    public function index(){
         $user = auth()->user();
        return view('respuestasEncuestas', ['codigoUsuario' => $user->id]);
    }
    public function inicializar(){
        $encuestas = encuesta::where(function($query){
            $query->where('estatus', 'ENCURSO')
                ->orWhere('estatus', 'FINALIZADO');
        })
        ->get(['id', 'nombre']);
        $personas = persona::where('deleted_at', null)
        ->get(['id', 'nombres', 'apellido_paterno', 'telefono_celular']);
        return [
            'encuestas' => $encuestas,
            'personas' => $personas
        ];
    }
    public function paginacion(Request $formulario){
        try {
            $draw = ($formulario->get('draw') != null) ? $formulario->get('draw') : 1;
            $start = ($formulario->get('start') != null) ? $formulario->get('start') : 0;
            $length = ($formulario->get('length') != null) ? $formulario->get('length') : 10;
            $filter = $formulario->get('search');
            $search = (isset($filter['value']))? $filter['value'] : false;

            $encuestasQuery = respuesta::join('encuestas', 'respuestas.encuesta_id', '=', 'encuestas.id');
            if ($search != false) {
                $encuestasQuery->where(function($query) use ($search) {
                    $query->where('nombre', 'LIKE', '%' . $search . '%')
                        ->orWhere('origen', 'LIKE', '%' . $search . '%')
                        ->orWhere('tipo', 'LIKE', '%' . $search . '%');
                });
            }
            if($formulario->selectNombre != 'TODOS'){
                $encuestasQuery->where('encuestas.id', $formulario->selectNombre);
            }
            $total = $encuestasQuery->count();

            $encuestas = $encuestasQuery->select(
                'respuestas.id',
                'nombre',
                'origen',
                'persona_id'
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
    public function cargarResultado(respuesta $respuesta, Request $formulario){
        return [
            'nombre' => $respuesta->encuesta->nombre,
            'preguntas' => $respuesta->encuesta->jsonPregunta,
            'resultados' => $respuesta->jsonRespuestas
        ];
    }
    public function exportarResultados(encuesta $encuesta, Request $formulario){
        $preguntasCompletas = json_decode($encuesta->jsonPregunta, true);
        $preguntasFiltradas = array_filter($preguntasCompletas, function($field) {
            return array_key_exists('name', $field);
        });
        $preguntas = [];
        $preguntasName = [];
        foreach ($preguntasFiltradas as $pregunta) {
            array_push($preguntas, $pregunta["label"]);
        }
        foreach ($preguntasFiltradas as $pregunta) {
            array_push($preguntasName, $pregunta["name"]);
        }
        $respuestas = respuesta::where('encuesta_id', $encuesta->id)->get()->map(function($respuesta){
            return json_decode($respuesta->jsonRespuestas, true);
        });
        return Excel::download(new resultadoExport($respuestas, $preguntasName, $preguntas), 'resultados_' . $encuesta->nombre . '.xlsx');
    }
    public function vincularPersona(respuesta $respuesta, persona $persona, Request $formulario){
        try{
            DB::beginTransaction();
                $respuesta->persona_id = $persona->id;
                $respuesta->save();
            DB::commit();
            session()->flash('mensajeExito', 'Se ha vinculado a una persona con éxito');
            return redirect()->route('respuestas.index');
        }
        catch(Exception $e){
            DB::rollBack();
            Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
            return back()->withErrors(['errorValidacion' => 'Ha ocurrido un error al registrar el usuario'])->withInput();
        }
    }
}
