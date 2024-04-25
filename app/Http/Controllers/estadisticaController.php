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

        //2 GRAFICAS
        //GRAFICA DE TIEMPO PARA VISUALIZAR REGISTROS HECHO EN EL TIEMPO
        //CUANDO ES COMPARATIVO DESPLEGAR TODAS LAS SECCIONES CON EL GRAFICO DE barras PARA COMPRAR, REGISTROS, META Y POBLACIÓN

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

        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $conteos = [];
        $dias = [];
        $maximo = 0;
        foreach ($registrosPorFechas as $registro) {
            $fechaActual = Carbon::parse($registro->fecha_registro)->format('d-F');
            $fecha = Carbon::parse($fechaActual);
            $mes = $meses[($fecha->format('n')) - 1];
            $fechaFormateada = $fecha->format('d') . ' de ' . $mes;
            array_push($dias, $fechaFormateada);
            array_push($conteos, $registro->conteoTotal);
            if($maximo < $registro->conteoTotal){
                $maximo = $registro->conteoTotal;
            }

        }


        $seccions = seccion::select('id', 'poblacion', 'objetivo')->get();

        return [
            'conteoSeparado' => $personas,
            'registrosPorFechas' => [
                'conteos' => $conteos,
                'fechas' => $dias,
                'maximo' => $maximo
            ],
            'seccionesAccesibles' => $seccionesParaBuscar,
            'seccionesConfigurarMetas' => $seccions
        ];
    }

    public function cargarMeta(Request $formulario){
        $meta = seccion::find($formulario->idSeccion);
        try {
            DB::beginTransaction();
            $meta->objetivo = $formulario->cantidadObjetivo;
            $meta->poblacion = $formulario->poblacion;
            $meta->save();
            DB::commit();
            return [1, 'Se ha modificado la sección: ' . $formulario->idSeccion . ' se recomienda recargar la página.'];
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage(). ' | Linea: ' . $e->getLine());
            return [0, 'Ha ocurrido un error al cambiar una meta.'];
        }
    }

    public function filtrar(Request $formulario){
        //DEVOLVER LISTADO DE SECCIONES CON SUS METAS Y POBLACIONES
        //UNA LISTA DE REGISTROS HECHOS CON FECHA Y SECCIÓN
        $banderaAgrupacion = $formulario->banderaAgrupacion;
        $seccionesSeleccionadas = $formulario->seccionesSeleccionadas;
        $fechaInicio = $formulario->fechaInicio;
        $fechaFin = $formulario->fechaFin;
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
        $queryPrincial = persona::where('deleted_at', null)
        ->join('identificacions', 'identificacions.persona_id', '=', 'personas.id');
        if(isset($fechaInicio)){
            $queryPrincial->whereDate('fecha_registro', '>=', $fechaInicio);
        }
        if(isset($fechaFin)){
            $queryPrincial->whereDate('fecha_registro', '<=', $fechaFin);
        }
        if($banderaAgrupacion  == 'COMPARATIVO'){
            $personas = $queryPrincial->join('seccions', 'identificacions.seccion_id', '=', 'seccions.id')
            ->whereIn('seccion_id', $seccionesParaBuscar)
            ->select('seccion_id', 'poblacion', 'objetivo', DB::raw('COUNT(*) as conteoTotal'))
            ->groupBy('seccion_id', 'poblacion', 'objetivo')
            ->get();

            $registrosPorFechas = $queryPrincial->whereIn('seccion_id', $seccionesParaBuscar)
            ->select('fecha_registro', DB::raw('COUNT(*) as conteoTotal'))
            ->groupBy('fecha_registro')
            ->orderBy('fecha_registro', 'ASC')
            ->get();

            $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
            $conteos = [];
            $dias = [];
            $maximo = 0;
            foreach ($registrosPorFechas as $registro) {
                $fechaActual = Carbon::parse($registro->fecha_registro)->format('d-F');
                $fecha = Carbon::parse($fechaActual);
                $mes = $meses[($fecha->format('n')) - 1];
                $fechaFormateada = $fecha->format('d') . ' de ' . $mes;
                array_push($dias, $fechaFormateada);
                array_push($conteos, $registro->conteoTotal);
                if($maximo < $registro->conteoTotal){
                    $maximo = $registro->conteoTotal;
                }

            }



            return [
                'tipo' => 'COMPARATIVO',
                'conteoSeparado' => $personas,
                'registrosPorFechas' => [
                    'conteos' => $conteos,
                    'fechas' => $dias,
                    'maximo' => $maximo,
                ],
            ];
        }
        else{
            //CUANDO ES AGRUPACIÓN, DESPLEGAR UNA GRAFICA DE BARRAS PARA COMPARAR LA SUMA DE TODOS LOS REGISTROS
            //CONTRA SUS METAS SUMADAS Y POBLACIONES SUMADAS
            //Y UNA GRAFICA DE PIE PARA VER EL PORCETANJE DE REGISTROS POR CADA SECCIÓN SELECCIONADA
            $personas = $queryPrincial->join('seccions', 'identificacions.seccion_id', '=', 'seccions.id')
            ->whereIn('seccion_id', $seccionesSeleccionadas)
            ->select('seccion_id', 'poblacion', 'objetivo', DB::raw('COUNT(*) as conteoTotal'))
            ->groupBy('seccion_id', 'poblacion', 'objetivo')
            ->get();

            $registrosPorFechas = $queryPrincial->whereIn('seccion_id', $seccionesSeleccionadas)
            ->select('fecha_registro', DB::raw('COUNT(*) as conteoTotal'))
            ->groupBy('fecha_registro')
            ->orderBy('fecha_registro', 'ASC')
            ->get();

            $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
            $conteos = [];
            $dias = [];
            $maximo = 0;
            foreach ($registrosPorFechas as $registro) {
                $fechaActual = Carbon::parse($registro->fecha_registro)->format('d-F');
                $fecha = Carbon::parse($fechaActual);
                $mes = $meses[($fecha->format('n')) - 1];
                $fechaFormateada = $fecha->format('d') . ' de ' . $mes;
                array_push($dias, $fechaFormateada);
                array_push($conteos, $registro->conteoTotal);
                if($maximo < $registro->conteoTotal){
                    $maximo = $registro->conteoTotal;
                }

            }



            return [
                'tipo' => 'AGRUPACION',
                'conteoSeparado' => $personas,
                'registrosPorFechas' => [
                    'conteos' => $conteos,
                    'fechas' => $dias,
                    'maximo' => $maximo,
                ],
            ];
        }

    }
}
