<?php

namespace App\Http\Controllers;

use App\Models\distritoFederal;
use App\Models\distritoLocal;
use App\Models\entidad;
use App\Models\meta;
use App\Models\persona;
use App\Models\seccion;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Format;

class estadisticaController extends Controller
{
    public function index(){
        return view('estadistica');
    }

    public function inicializar(){
        //DEVOLVER LISTADO DE SECCIONES CON SUS METAS Y POBLACIONES
        //UNA LISTA DE REGISTROS HECHOS CON FECHA Y SECCIÓN
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

        $personas = persona::join('identificacions', 'identificacions.persona_id', '=', 'personas.id')
        ->join('seccions', 'identificacions.seccion_id', '=', 'seccions.id')
        ->whereIn('seccion_id', $seccionesParaBuscar)
        ->select('seccion_id', 'poblacion', 'objetivo', DB::raw('COUNT(*) as conteoTotal'))
        ->groupBy('seccion_id', 'poblacion', 'objetivo')
        ->get();

        $registrosPorFechas = persona::join('identificacions', 'identificacions.persona_id', '=', 'personas.id')
        ->whereIn('seccion_id', $seccionesParaBuscar)
        ->select('fecha_registro', DB::raw('COUNT(*) as conteoTotal'))
        ->groupBy('fecha_registro')
        ->orderBy('fecha_registro', 'ASC')
        ->get();

        $seccions = seccion::select('id', 'poblacion', 'objetivo')->get();

        return [
            'conteoSeparado' => $personas,
            'registrosPorFechas' => $registrosPorFechas,
            'seccionesAccesibles' => $seccionesParaBuscar,
            'seccionesConfigurarMetas' => $seccions
        ];
    }

    public function cargarMeta(Request $formulario){
        $formulario->validate([
            'cantidadObjetivo' => 'required|numeric',
            'poblacion' => 'required|numeric',
        ]);
        $meta = meta::find(1);
        try {
            DB::beginTransaction();
            $meta->numeroObjetivo = $formulario->cantidadObjetivo;
            $meta->poblacionEstablecida = $formulario->poblacion;
            $meta->save();
            DB::commit();
            session()->flash('mensajeExito', 'Meta cargada con éxito');
            return redirect()->route('estadistica.index');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
            return back()->withErrors(['errorValidacion' => 'Ha ocurrido un error al cargar la meta'])->withInput();
        }
    }

    public function filtrar($banderaAgrupacion, $seccionesSeleccionadas, $fechaInicio, $fechaFin){
        //DEVOLVER LISTADO DE SECCIONES CON SUS METAS Y POBLACIONES
        //UNA LISTA DE REGISTROS HECHOS CON FECHA Y SECCIÓN
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

        $personas = persona::join('identificacions', 'identificacions.persona_id', '=', 'personas.id')
        ->join('seccions', 'identificacions.seccion_id', '=', 'seccions.id')
        ->whereIn('seccion_id', $seccionesParaBuscar)
        ->select('seccion_id', 'poblacion', 'objetivo', DB::raw('COUNT(*) as conteoTotal'))
        ->groupBy('seccion_id', 'poblacion', 'objetivo')
        ->get();

        $registrosPorFechas = persona::join('identificacions', 'identificacions.persona_id', '=', 'personas.id')
        ->whereIn('seccion_id', $seccionesParaBuscar)
        ->select('fecha_registro', DB::raw('COUNT(*) as conteoTotal'))
        ->groupBy('fecha_registro')
        ->orderBy('fecha_registro', 'ASC')
        ->get();

        $seccions = seccion::select('id', 'poblacion', 'objetivo')->get();

        return [
            'conteoSeparado' => $personas,
            'registrosPorFechas' => $registrosPorFechas,
            'seccionesAccesibles' => $seccionesParaBuscar,
            'seccionesConfigurarMetas' => $seccions
        ];
        // $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
            // $fechaActual = Carbon::now();
            // $conteos = [];
            // $dias = [];
            // $maximo = 0;
            // $user = auth()->user();
            // if($user->getRoleNames()->first() == 'SUPER ADMINISTRADOR' || $user->getRoleNames()->first() == 'ADMINISTRADOR'){
            //     for ($i = 13; $i >= 0; $i--) {
            //         $fecha = $fechaActual->copy()->subDays($i)->toDateString();
            //         $fechaFormateada = $fechaActual->copy()->subDays($i)->format('d-F');

            //         $fecha = Carbon::parse($fechaFormateada);
            //         $mes = $meses[($fecha->format('n')) - 1];
            //         $fechaFormateada = $fecha->format('d') . ' de ' . $mes;

            //         $conteo = persona::whereDate('created_at', $fecha)->count();
            //         array_push($conteos, $conteo);
            //         array_push($dias, $fechaFormateada);
            //         if($maximo < $conteo){
            //             $maximo = $conteo;
            //         }
            //     }

            //     $conteoRegistrosPorDia = [
            //         'fechas' => $dias,
            //         'totales' => $conteos,
            //         'maximo' => $maximo
            //     ];
            //     $meta = meta::find(1);
            //     $numeroPersonas = persona::count();
            //     $numeroSimpatizantes = persona::where('simpatizante', 'SI')->count();

            //     return [
            //         [
            //             $numeroPersonas,
            //             $numeroSimpatizantes,
            //             $meta->numeroObjetivo,
            //             $meta->poblacionEstablecida
            //         ],
            //         $conteoRegistrosPorDia,
            //         [
            //             $numeroPersonas,
            //             $meta->poblacionEstablecida - $numeroPersonas
            //         ],
            //         [
            //             $numeroPersonas,
            //             $meta->numeroObjetivo - $numeroPersonas
            //         ]
            //     ];
            // }
            // else{
            //     // $user = auth()->user();
            //     // // return $user;
            //     // $niveles = isset($user->niveles) ? explode( ',', $user->niveles) : null;
            //     // return $niveles; //APLICAR TRIM A CADA NIVEL
            //     for ($i = 13; $i >= 0; $i--) {
            //         $fecha = $fechaActual->copy()->subDays($i)->toDateString();
            //         $fechaFormateada = $fechaActual->copy()->subDays($i)->format('d-F');

            //         $fecha = Carbon::parse($fechaFormateada);
            //         $mes = $meses[($fecha->format('n')) - 1];
            //         $fechaFormateada = $fecha->format('d') . ' de ' . $mes;

            //         $conteo = persona::whereDate('created_at', $fecha)->count();
            //         array_push($conteos, $conteo);
            //         array_push($dias, $fechaFormateada);
            //         if($maximo < $conteo){
            //             $maximo = $conteo;
            //         }
            //     }

            //     $conteoRegistrosPorDia = [
            //         'fechas' => $dias,
            //         'totales' => $conteos,
            //         'maximo' => $maximo
            //     ];
            //     $meta = meta::find(1);
            //     $numeroPersonas = persona::count();
            //     $numeroSimpatizantes = persona::where('simpatizante', 'SI')->count();

            //     return [
            //         [
            //             $numeroPersonas,
            //             $numeroSimpatizantes,
            //             $meta->numeroObjetivo,
            //             $meta->poblacionEstablecida
            //         ],
            //         $conteoRegistrosPorDia,
            //         [
            //             $numeroPersonas,
            //             $meta->poblacionEstablecida - $numeroPersonas
            //         ],
            //         [
            //             $numeroPersonas,
            //             $meta->numeroObjetivo - $numeroPersonas
            //         ]
            //     ];
        // }
    }
}
