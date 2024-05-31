<?php

namespace App\Http\Controllers;

use App\Models\bitacora;
use App\Models\distritoFederal;
use App\Models\distritoLocal;
use App\Models\encuesta;
use App\Models\entidad;
use App\Models\persona;
use App\Models\pregunta;
use App\Models\respuesta;
use App\Models\seccion;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class crudEncuestasController extends Controller
{
    public function index(){
        // $user = auth()->user();
        // $bitacora = new bitacora();
        // $bitacora->accion = 'entrando en la vista crud encuestas : ' . $user->email;
        // $bitacora->url = url()->current();
        // $bitacora->ip = $formulario->ip();
        // $bitacora->tipo = 'vista';
        // $bitacora->user_id = $user->id;
        // $bitacora->save();

        // $roles = Role::where('name', '!=', 'SUPER ADMINISTRADOR')->get(['name']);
        // return view('crudEncuestas', compact('roles'));

        $encuestas = encuesta::where('deleted_at', null)
        ->where('estatus', 'ENCURSO')
        ->get();
        foreach ($encuestas as $encuesta) {
            if($encuesta->cierreAutomatico && isset($encuesta->fecha_fin)){
                $fechaCierre = Carbon::parse($encuesta->fecha_fin);
                if ($fechaCierre->isToday() || $fechaCierre->isPast()) {
                    try{
                        DB::beginTransaction();
                        $encuesta->estatus = "FINALIZADO";
                        $encuesta->fecha_fin_sistema = Date("Y-m-d H:i:s");
                        $encuesta->save();
                        DB::commit();
                    }
                    catch(Exception $e){
                        DB::rollBack();
                        Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
                    }
                }
            }
        }
        $user = auth()->user();
        return view('crudEncuestas', ['codigoPromotor' => $user->id]);
    }
    public function cargarEncuestas(Request $formulario){
        if(isset($formulario->fechaInicio)){
            $fechaInicio = DateTime::createFromFormat('Y-m-d', $formulario->fechaInicio);
            $fechaInicio = $fechaInicio->format('Y/m/d');
        }
        if(isset($formulario->fechaFin)){
            $fechaFin = DateTime::createFromFormat('Y-m-d', $formulario->fechaFin);
            $fechaFin = $fechaFin->format('Y/m/d');
        }
        try {
            $draw = ($formulario->get('draw') != null) ? $formulario->get('draw') : 1;
            $start = ($formulario->get('start') != null) ? $formulario->get('start') : 0;
            $length = ($formulario->get('length') != null) ? $formulario->get('length') : 10;
            $filter = $formulario->get('search');
            $search = (isset($filter['value']))? $filter['value'] : false;


            $encuestasQuery = encuesta::where('deleted_at', null);
            if ($search != false) {
                $encuestasQuery->where(function($query) use ($search) {
                    $query->where('nombre', 'LIKE', '%' . $search . '%')
                        ->orWhere('fecha_inicio', 'LIKE', '%' . $search . '%')
                        ->orWhere('fecha_fin', 'LIKE', '%' . $search . '%');
                });
            }
            if(isset($formulario->fechaInicio)){
                $encuestasQuery->where('created_at', '>=', $fechaInicio);
            }
            if(isset($formulario->fechaFin)){
                $encuestasQuery->where('created_at', '<=', $fechaFin);
            }
            $total = $encuestasQuery->count();

            $encuestas = $encuestasQuery->select(
                'id',
                'nombre',
                DB::raw('DATE_FORMAT(fecha_inicio, "%d/%m/%Y") as fecha_inicio'),
                DB::raw('DATE_FORMAT(fecha_fin, "%d/%m/%Y") as fecha_fin'),
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
    public function cargarSecciones(){
        return  [
            'secciones' => seccion::pluck('id')->toArray(),
            'promotores' => persona::where('deleted_at', null)->where('rolEstructura', 'PROMOTOR')->get()
        ];

    }
    public function agregar(Request $formulario){
        session()->flash('encuestaCrearErrores', true);
        try{
            $user = auth()->user();
            DB::beginTransaction();
            $nuevaEncuesta = new encuesta();
            $nuevaEncuesta->user_id = $user->id;
            $nuevaEncuesta->nombre = $formulario->nombreEncuesta;
            $nuevaEncuesta->fecha_inicio = $formulario->fechaInicio;
            $nuevaEncuesta->fecha_fin = $formulario->fechaFin;
            if(isset($formulario->buscarBaseDatos) && $formulario->buscarBaseDatos == true){
                $nuevaEncuesta->buscarBaseDatos = true;
            }
            if(isset($formulario->cierreAutomatico) && $formulario->cierreAutomatico == true){
                $nuevaEncuesta->cierreAutomatico = true;
            }
            if(isset($formulario->preguntasJSON) && $formulario->preguntasJSON != ''){
                $nuevaEncuesta->jsonPregunta = $formulario->preguntasJSON;
            }
            $nuevaEncuesta->save();
            DB::commit();
            session()->forget('encuestaCrearErrores');
            session()->flash('mensajeExito', 'La encuesta se ha creada con éxito');
            return redirect()->route('encuestas.index');
        }
        catch(Exception $e){
            DB::rollBack();
            Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
            return back()->withErrors(['errorValidacion' => 'Ha ocurrido un error al crear una encuesta'])->withInput();
        }
    }
    public function ver(encuesta $encuesta){
        return $encuesta;
    }
    public function editar(encuesta $encuesta, Request $formulario){
        if($encuesta->estatus == 'CREANDO'){
            session()->flash('encuestaModificarErrores', true);
            try{
                $user = auth()->user();
                DB::beginTransaction();
                $encuesta->user_id = $user->id;
                $encuesta->nombre = $formulario->descripcion;
                $encuesta->fecha_inicio = $formulario->fechaInicio;
                $encuesta->fecha_fin = $formulario->fechaFin;
                if(isset($formulario->buscarBaseDatos) && $formulario->buscarBaseDatos == true){
                    $encuesta->buscarBaseDatos = true;
                }
                else{
                    $encuesta->buscarBaseDatos = false;
                }
                if(isset($formulario->cierreAutomaticoModificar) && $formulario->cierreAutomaticoModificar == true){
                    $encuesta->cierreAutomatico = true;
                }
                else{
                    $encuesta->cierreAutomatico = false;
                }
                if(isset($formulario->ModificarPreguntasJSON) && $formulario->ModificarPreguntasJSON != ''){
                    $encuesta->jsonPregunta = $formulario->ModificarPreguntasJSON;
                }
                $encuesta->save();
                DB::commit();
                session()->forget('encuestaModificarErrores');
                session()->flash('mensajeExito', 'La encuesta se ha modificado con éxito');
                return redirect()->route('encuestas.index');
            }
            catch(Exception $e){
                DB::rollBack();
                Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
                return back()->withErrors(['errorValidacion' => 'Ha ocurrido un error al modificar una encuesta'])->withInput();
            }
        }
        else{
            return back()->withErrors(['errorValidacion' => 'No se puede editar una encuesta en curso o finalizada.'])->withInput();
        }
    }
    public function borrar(encuesta $encuesta, Request $formulario){
        try{
            DB::beginTransaction();
            $encuesta->deleted_at = Date("Y-m-d H:i:s");
            $encuesta->save();
            DB::commit();
            session()->flash('mensajeExito', 'La encuesta ' . $encuesta->nombre . ' ha sido eliminada con éxito.');
            return redirect()->route('encuestas.index');
        }
        catch(Exception $e){
            DB::rollBack();
            Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
            return back()->withErrors(['errorValidacion' => 'Ha ocurrido un error al registrar el usuario'])->withInput();
        }
    }
    public function configurar(encuesta $encuesta, Request $formulario){

        session()->flash('encuestaConfigurarErrores', true);
        try{
            DB::beginTransaction();
            $cadenaToArray = '';
            if(isset($formulario->seccionesObjetivo)){
                foreach ($formulario->seccionesObjetivo as $seccion) {
                    $cadenaToArray .= $seccion . ',';
                }
                $cadenaToArray = substr($cadenaToArray, 0, -1);
                $encuesta->seccionesObjetivo = $cadenaToArray;
            }
            // $encuesta->tipoGrafica = $formulario->tipoGrafica;
            $encuesta->save();
            DB::commit();
            session()->forget('encuestaConfigurarErrores');
            session()->flash('mensajeExito', 'La encuesta '.$encuesta->nombre.' se ha configurado con éxito');
            return redirect()->route('encuestas.index');
        }
        catch(Exception $e){
            DB::rollBack();
            Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
            return back()->withErrors(['errorValidacion' => 'Ha ocurrido un error al configurar una encuesta'])->withInput();
        }
    }
    public function clonar(encuesta $encuesta, Request $formulario){
        try{
            $user = auth()->user();
            DB::beginTransaction();
            $nuevaEncuesta = new encuesta();
            $nuevaEncuesta->user_id = $user->id;
            $nuevaEncuesta->nombre = $encuesta->nombre . '-COPIA';
            $nuevaEncuesta->buscarBaseDatos = $encuesta->buscarBaseDatos;
            $nuevaEncuesta->fecha_inicio = $encuesta->fecha_inicio;
            $nuevaEncuesta->fecha_fin = $encuesta->fecha_fin;
            if(isset($encuesta->jsonPregunta) && $encuesta->jsonPregunta != ''){
                $nuevaEncuesta->jsonPregunta = str_replace(",{\"type\":\"header\",\"subtype\":\"h3\",\"label\":\"Datos de identificaci\\u00f3n\",\"access\":false},{\"type\":\"text\",\"required\":false,\"label\":\"Nombres\",\"className\":\"form-control\",\"name\":\"nombres\",\"access\":false,\"subtype\":\"text\"},{\"type\":\"text\",\"required\":false,\"label\":\"Apellido paterno\",\"className\":\"form-control\",\"name\":\"apellidoPaterno\",\"access\":false,\"subtype\":\"text\"},{\"type\":\"text\",\"required\":false,\"label\":\"Telefono\",\"className\":\"form-control\",\"name\":\"telefono\",\"access\":false,\"subtype\":\"text\"}", '',  $encuesta->jsonPregunta);
            }
            $nuevaEncuesta->save();
            DB::commit();
            session()->flash('mensajeExito', 'La encuesta se ha duplicado con éxito con el nombre: ' . $encuesta->nombre . '-COPIA');
            return redirect()->route('encuestas.index');
        }
        catch(Exception $e){
            DB::rollBack();
            Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
            return back()->withErrors(['errorValidacion' => 'Ha ocurrido un error al duplicar una encuesta'])->withInput();
        }
    }
    public function vistaPrevia(encuesta $encuesta, Request $formulario){
         try{
            // DB::beginTransaction();

            // DB::commit();
            // session()->forget('formularioCrearErrores');
            // session()->flash('mensajeExito', 'Usuario creado con exito');
            // return redirect()->route('crudUsuario.index');
        }
        catch(Exception $e){
            // DB::rollBack();
            // Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
            // return back()->withErrors(['errorValidacion' => 'Ha ocurrido un error al registrar el usuario'])->withInput();
        }
    }
    public function cargarVistaPrevia(encuesta $encuesta, Request $formulario){
         try{
            // DB::beginTransaction();

            // DB::commit();
            // session()->forget('formularioCrearErrores');
            // session()->flash('mensajeExito', 'Usuario creado con exito');
            // return redirect()->route('crudUsuario.index');
        }
        catch(Exception $e){
            // DB::rollBack();
            // Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
            // return back()->withErrors(['errorValidacion' => 'Ha ocurrido un error al registrar el usuario'])->withInput();
        }
    }
    public function iniciarEncuesta(encuesta $encuesta, Request $formulario){
        if($encuesta->jsonPregunta != '[]'){
            try{
                DB::beginTransaction();


                if($encuesta->buscarBaseDatos){
                    //Agregar nombres, apellido paterno y celular
                    $preguntasCompletas = json_decode($encuesta->jsonPregunta);
                    $titulo = [
                        "type" => "header",
                        "subtype" => "h3",
                        "label" => "Datos de identificación",
                        "access" => false
                    ];
                    $preugntaNombre =  [
                        "type" => "text",
                        "required" => false,
                        "label" => "Nombres",
                        "className" => "form-control",
                        "name" => "nombres",
                        "access" => false,
                        "subtype" => "text"
                    ];
                    $preguntaApellidoPaterno =  [
                        "type" => "text",
                        "required" => false,
                        "label" => "Apellido paterno",
                        "className" => "form-control",
                        "name" => "apellidoPaterno",
                        "access" => false,
                        "subtype" => "text"
                    ];
                    $preguntaTelefono =  [
                        "type" => "text",
                        "required" => false,
                        "label" => "Telefono",
                        "className" => "form-control",
                        "name" => "telefono",
                        "access" => false,
                        "subtype" => "text"
                    ];
                    array_push($preguntasCompletas, $titulo);
                    array_push($preguntasCompletas, $preugntaNombre);
                    array_push($preguntasCompletas, $preguntaApellidoPaterno);
                    array_push($preguntasCompletas, $preguntaTelefono);
                    $encuesta->jsonPregunta = $preguntasCompletas;
                }

                $encuesta->estatus = "ENCURSO";
                $encuesta->fecha_inicio_sistema = Date("Y-m-d H:i:s");
                $encuesta->save();
                DB::commit();
                session()->flash('mensajeExito', 'La encuesta ' . $encuesta->nombre . ' esta en curso. Ahora puede compartir el enlace de la encuesta.');
                return redirect()->route('encuestas.index');
            }
            catch(Exception $e){
                DB::rollBack();
                return back()->withErrors(['errorValidacion' => 'Ha ocurrido un error al registrar el usuario'])->withInput();
            }
        }
        else{
            return back()->withErrors(['errorValidacion' => 'La encuesta no puede iniciar si no tiene ninguna pregunta'])->withInput();
        }
    }
    public function detenerEncuesta(encuesta $encuesta, Request $formulario){
        try{
            DB::beginTransaction();
            $encuesta->estatus = "FINALIZADO";
            $encuesta->fecha_fin_sistema = Date("Y-m-d H:i:s");
            $encuesta->save();
            DB::commit();
            session()->flash('mensajeExito', 'La encuesta ' . $encuesta->nombre . ' ha finalizado. Compruebe sus resultados en el módulo de estadística.');
            return redirect()->route('encuestas.index');
        }
        catch(Exception $e){
            DB::rollBack();
            Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
            return back()->withErrors(['errorValidacion' => 'Ha ocurrido un error al registrar el usuario'])->withInput();
        }
    }
    public function enviarCorreo(encuesta $encuesta, Request $formulario){
         try{
            // DB::beginTransaction();

            // DB::commit();
            // session()->forget('formularioCrearErrores');
            // session()->flash('mensajeExito', 'Usuario creado con exito');
            // return redirect()->route('crudUsuario.index');
        }
        catch(Exception $e){
            // DB::rollBack();
            // Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
            // return back()->withErrors(['errorValidacion' => 'Ha ocurrido un error al registrar el usuario'])->withInput();
        }
    }

    public function visualizarEncuesta(encuesta $encuesta, Request $formulario){
        if($encuesta->cierreAutomatico && isset($encuesta->fechFin)){
            $fechaCierre = Carbon::parse($encuesta->fecha_fin);
            if ($fechaCierre->isToday() || $fechaCierre->isPast()) {
                try{
                    DB::beginTransaction();
                    $encuesta->estatus = "FINALIZADO";
                    $encuesta->fecha_fin_sistema = Date("Y-m-d H:i:s");
                    $encuesta->save();
                    DB::commit();
                }
                catch(Exception $e){
                    DB::rollBack();
                    Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
                }
            }
        }
        if($encuesta->estatus == 'ENCURSO'){
            try{
                $codigoPromotor = 0;
                if(isset($formulario->codigoPromotor) && $formulario->codigoPromotor > 0){
                    $usuarioExiste = persona::find($formulario->codigoPromotor);
                    $codigoPromotor = $usuarioExiste->id;
                }
                return view('responderEncuesta',
                ['idEncuesta' =>$encuesta->id, 'codigoPromotor' => $codigoPromotor,
                'origen' => $formulario->origen,
                'nombreEncuesta' => $encuesta->nombre]);
            }
            catch(Exception $e){
                Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
                return back()->withErrors(['errorValidacion' => 'Ha ocurrido un error al registrar el usuario'])->withInput();
            }
        }else{
            return abort(403);
        }
    }
    public function cargarEncuesta(encuesta $encuesta, Request $formulario){
        return $encuesta->jsonPregunta;
    }
    public function contestarEncuesta(encuesta $encuesta, Request $formulario){
        $preguntasDinamicas = $formulario->except('_token', 'usuarioRelacionado', 'origen');
        $preguntasDinamicas = json_encode($preguntasDinamicas);
        try{
            DB::beginTransaction();
                $nuevaRespuesta = new respuesta();
                $nuevaRespuesta->folio = 1;
                if($encuesta->buscarBaseDatos){
                    $nuevaRespuesta->nombres = strtoupper($formulario->nombres);
                    $nuevaRespuesta->apellidos = strtoupper($formulario->apellidoPaterno);
                    $nuevaRespuesta->telefono = $formulario->telefono;
                    $personaEncontrada = persona::where('nombres', strtoupper($formulario->nombres))
                    ->where('apellido_paterno', strtoupper($formulario->apellidoPaterno))
                    ->where('telefono_celular', $formulario->telefono)
                    ->first();
                    if(isset($personaEncontrada)){
                        $nuevaRespuesta->persona_id = $personaEncontrada->id;
                    }
                }
                $nuevaRespuesta->promotor_id = $formulario->usuarioRelacionado;
                $nuevaRespuesta->encuesta_id = $encuesta->id;
                $nuevaRespuesta->origen = $formulario->origen;
                $nuevaRespuesta->jsonRespuestas = $preguntasDinamicas;
                $nuevaRespuesta->save();

            DB::commit();
            session()->flash('mensajeExito', 'Formulario enviado con exito');
            return redirect()->route('encuestas.graciasResponder');
        }
        catch(Exception $e){
            DB::rollBack();
            Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
            return back()->withErrors(['errorValidacion' => 'Ha ocurrido un error al registrar el usuario'])->withInput();
        }
    }
    public function graciasResponder(){
        return view('graciasEncuesta');
    }

}
