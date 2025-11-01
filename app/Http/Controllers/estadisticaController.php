<?php

namespace App\Http\Controllers;

use App\Exports\MetaExport;
use App\Models\distritoFederal;
use App\Models\distritoLocal;
use App\Models\entidad;
use App\Models\meta;
use App\Models\municipio;
use App\Models\persona;
use App\Models\seccion;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Format;
use Illuminate\Database\Eloquent\Builder;

use function ElementorDeps\DI\get;

class estadisticaController extends Controller
{
    public function index(){
         $distritosFederales = DistritoFederal::orderBy('id')->get();
        return view('estadistica', compact('distritosFederales'));
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

    public function filtrar(Request $request){

        $query = Persona::with([
            'identificacion.seccion.distritoLocal.municipio.distritoFederal'
        ])->where('deleted_at', null);

        $query
        ->when(isset($request->fechaInicio), function ($q) use ($request) {
            $q->whereDate('fecha_registro', '>=', $request->fechaInicio);
        })
        ->when(isset($request->fechaFin), function ($q) use ($request) {
            $q->whereDate('fecha_registro', '<=', $request->fechaFin);
        })
        ->when(
            $request->filled('distrito_federal') && !in_array(0, $request->distrito_federal),
            fn ($q) => $q->whereHas('identificacion.seccion.distritoLocal.municipio.distritoFederal', function (Builder $sub) use ($request) {
                $sub->whereIn('id', $request->distrito_federal);
            })
        )
        ->when(
            $request->filled('municipio') && !in_array(0, $request->municipio),
            fn ($q) => $q->whereHas('identificacion.seccion.distritoLocal.municipio', function (Builder $sub) use ($request) {
                $sub->whereIn('id', $request->municipio);
            })
        )
        ->when(
            $request->filled('distrito_local') && !in_array(0, $request->distrito_local),
            fn ($q) => $q->whereHas('identificacion.seccion.distritoLocal', function (Builder $sub) use ($request) {
                $sub->whereIn('id', $request->distrito_local);
            })
        )
        ->when(
            $request->filled('seccion') && !in_array(0, $request->seccion),
            fn ($q) => $q->whereIn('seccion_id', $request->seccion)
        );

        $conteoSupervisados = clone $query->select(
                DB::raw('CASE WHEN personas.supervisado = 1 THEN "SUPERVISADO" ELSE "NO SUPERVISADO" END as supervisado'),
                DB::raw('COUNT(*) as conteoTotal')
            )
            ->groupBy('supervisado')
            ->get();

        $desglozadoSecciones = seccion::leftJoin('identificacions', 'identificacions.seccion_id', '=', 'seccions.id')
        ->leftJoin('personas', 'personas.id', '=', 'identificacions.persona_id')
        ->where('personas.deleted_at', null)
        ->when(isset($request->fechaInicio), function ($q) use ($request) {
            $q->whereDate('fecha_registro', '>=', $request->fechaInicio);
        })
        ->when(isset($request->fechaFin), function ($q) use ($request) {
            $q->whereDate('fecha_registro', '<=', $request->fechaFin);
        })
        ->when(
            $request->filled('distrito_federal') && !in_array(0, $request->distrito_federal),
            fn ($q) => $q->whereHas('distritoLocal.municipio.distritoFederal', function (Builder $sub) use ($request) {
                $sub->whereIn('id', $request->distrito_federal);
            })
        )
        ->when(
            $request->filled('municipio') && !in_array(0, $request->municipio),
            fn ($q) => $q->whereHas('distritoLocal.municipio', function (Builder $sub) use ($request) {
                $sub->whereIn('id', $request->municipio);
            })
        )
        ->when(
            $request->filled('distrito_local') && !in_array(0, $request->distrito_local),
            fn ($q) => $q->whereHas('distritoLocal', function (Builder $sub) use ($request) {
                $sub->whereIn('id', $request->distrito_local);
            })
        )
        ->when(
            $request->filled('seccion') && !in_array(0, $request->seccion),
            fn ($q) => $q->whereIn('id', $request->seccion)
        )
        ->select(
            'seccions.id',
            DB::raw('COUNT(personas.id) as conteoTotal'),
            'seccions.poblacion as poblacion',
            'seccions.objetivo as objetivo',
        )
        ->groupBy('seccions.id', 'seccions.poblacion', 'seccions.objetivo')
        ->orderBy('conteoTotal', 'Desc')
        ->get();

        DB::statement("SET lc_time_names = 'es_ES';");

        $registrosEnElTiempo = clone $query->select(
            DB::raw("DATE_FORMAT(fecha_registro, '%d/%b/%y') as fecha"),
            DB::raw('COUNT(*) as conteoTotal')
        )
        ->groupBy('fecha', 'fecha_registro')
        ->orderBy('fecha_registro', 'ASC')
        ->get();

        $registrosSinSeccion =  Persona::join('identificacions', 'identificacions.persona_id', '=', 'personas.id')
        ->where('deleted_at', null)
        ->when(isset($request->fechaInicio), function ($q) use ($request) {
            $q->whereDate('fecha_registro', '>=', $request->fechaInicio);
        })
        ->when(isset($request->fechaFin), function ($q) use ($request) {
            $q->whereDate('fecha_registro', '<=', $request->fechaFin);
        })
        ->when(
            $request->filled('distrito_federal') && !in_array(0, $request->distrito_federal),
            fn ($q) => $q->whereHas('identificacion.seccion.distritoLocal.municipio.distritoFederal', function (Builder $sub) use ($request) {
                $sub->whereIn('id', $request->distrito_federal);
            })
        )
        ->when(
            $request->filled('municipio') && !in_array(0, $request->municipio),
            fn ($q) => $q->whereHas('identificacion.seccion.distritoLocal.municipio', function (Builder $sub) use ($request) {
                $sub->whereIn('id', $request->municipio);
            })
        )
        ->when(
            $request->filled('distrito_local') && !in_array(0, $request->distrito_local),
            fn ($q) => $q->whereHas('identificacion.seccion.distritoLocal', function (Builder $sub) use ($request) {
                $sub->whereIn('id', $request->distrito_local);
            })
        )
        ->when(
            $request->filled('seccion') && !in_array(0, $request->seccion),
            fn ($q) => $q->whereIn('seccion_id', $request->seccion)
        )
        ->selectRaw('
            SUM(CASE WHEN identificacions.seccion_id IS NOT NULL THEN 1 ELSE 0 END) as con_seccion,
            SUM(CASE WHEN identificacions.seccion_id IS NULL THEN 1 ELSE 0 END) as sin_seccion
        ')
        ->first();


        $acumuladoSecciones = $desglozadoSecciones->reduce(function ($carry, $item) {
            $carry['conteoTotal'] = ($carry['conteoTotal'] ?? 0) + $item['conteoTotal'];
            $carry['poblacion']   = ($carry['poblacion'] ?? 0) + $item['poblacion'];
            $carry['objetivo']    = ($carry['objetivo'] ?? 0) + $item['objetivo'];
            return $carry;
        }, []);

        return[
            'conteoSupervisados' => $conteoSupervisados,
            'desglozadoSecciones' => $desglozadoSecciones,
            'registrosEnElTiempo' => $registrosEnElTiempo,
            'acumuladoSecciones' => $acumuladoSecciones,
            'registrosSinSeccion' => $registrosSinSeccion,
        ];


        //DEVOLVER LISTADO DE SECCIONES CON SUS METAS Y POBLACIONES
        //UNA LISTA DE REGISTROS HECHOS CON FECHA Y SECCIÓN
        // $banderaAgrupacion = $request->banderaAgrupacion;
        // $seccionesSeleccionadas = $request->seccionesSeleccionadas;
        // $fechaInicio = $request->fechaInicio;
        // $fechaFin = $request->fechaFin;
        // $user = auth()->user();
        // switch ($user->nivel_acceso) {
        //     case 'TODO':
        //             //HACER CONSULTA SIN FILTROS
        //             $seccionesParaBuscar = seccion::pluck('id')->toArray();
        //         break;
        //     case 'ENTIDAD':
        //             //HACER CONSULTA FILTRAR PERSONAS QUE SU IDENTIFICACION
        //             //PERTENEZCA A LA LISTA DE SECCIONES PERTENECIENTES A LAS ENTIDADES SELECCIONADAS
        //         $nivelesConAcceso = explode(',', $user->niveles);
        //         $seccionesParaBuscar = entidad::whereIn('entidads.id', $nivelesConAcceso)
        //         ->join('distrito_federals', 'entidads.id', '=','distrito_federals.entidad_id')
        //         ->join('municipios', 'distrito_federals.id', '=','municipios.distrito_federal_id')
        //         ->join('distrito_locals', 'municipios.id', '=','distrito_locals.municipio_id')
        //         ->join('seccions', 'distrito_locals.id', '=','seccions.distrito_local_id')
        //         ->pluck('seccions.id')
        //         ->toArray();

        //         break;
        //     case 'DISTRITO FEDERAL':
        //             //HACER CONSULTA FILTRAR PERSONAS QUE SU IDENTIFICACION
        //             //PERTENEZCA A LA LISTA DE SECCIONES PERTENECIENTES A LOS DISTRITOS FEDERALES SELECCIONADAS
        //         $nivelesConAcceso = explode(',', $user->niveles);
        //         $seccionesParaBuscar = distritoFederal::whereIn('distrito_federals.id', $nivelesConAcceso)
        //         ->join('municipios', 'distrito_federals.id', '=','municipios.distrito_federal_id')
        //         ->join('distrito_locals', 'municipios.id', '=','distrito_locals.municipio_id')
        //         ->join('seccions', 'distrito_locals.id', '=','seccions.distrito_local_id')
        //         ->pluck('seccions.id')
        //         ->toArray();

        //         break;
        //     case 'DISTRITO LOCAL':
        //             //HACER CONSULTA FILTRAR PERSONAS QUE SU IDENTIFICACION
        //             //PERTENEZCA A LA LISTA DE SECCIONES PERTENECIENTES A LOS DISTRITOS LOCALES SELECCIONADAS
        //         $nivelesConAcceso = explode(',', $user->niveles);
        //         $seccionesParaBuscar = distritoLocal::whereIn('distrito_locals.id', $nivelesConAcceso)
        //         ->join('seccions', 'distrito_locals.id', '=','seccions.distrito_local_id')
        //         ->pluck('seccions.id')
        //         ->toArray();

        //         break;
        //     case 'SECCION':
        //             //HACER CONSULTA FILTRAR PERSONAS QUE SU IDENTIFICACION
        //             //PERTENEZCA A LA LISTA DE SECCIONES PERTENECIENTES A LAS SECCIONES SELECCIONADAS
        //         $seccionesParaBuscar = explode(',', $user->niveles);
        //         $seccionesParaBuscar = array_map('intval', $seccionesParaBuscar);

        //         break;
        // }
        // $queryPrincial = persona::where('deleted_at', null)
        // ->join('identificacions', 'identificacions.persona_id', '=', 'personas.id');
        // if(isset($fechaInicio)){
        //     $queryPrincial->whereDate('fecha_registro', '>=', $fechaInicio);
        // }
        // if(isset($fechaFin)){
        //     $queryPrincial->whereDate('fecha_registro', '<=', $fechaFin);
        // }
        // if($banderaAgrupacion  == 'COMPARATIVO'){
        //     $personas = $queryPrincial->join('seccions', 'identificacions.seccion_id', '=', 'seccions.id')
        //     ->whereIn('seccion_id', $seccionesParaBuscar)
        //     ->select('seccion_id', 'poblacion', 'objetivo', DB::raw('COUNT(*) as conteoTotal'))
        //     ->groupBy('seccion_id', 'poblacion', 'objetivo')
        //     ->get();

        //     $registrosPorFechas = $queryPrincial->whereIn('seccion_id', $seccionesParaBuscar)
        //     ->select('fecha_registro', DB::raw('COUNT(*) as conteoTotal'))
        //     ->groupBy('fecha_registro')
        //     ->orderBy('fecha_registro', 'ASC')
        //     ->get();

        //     $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        //     $conteos = [];
        //     $dias = [];
        //     $maximo = 0;
        //     foreach ($registrosPorFechas as $registro) {
        //         $fechaActual = Carbon::parse($registro->fecha_registro)->format('d-F');
        //         $fecha = Carbon::parse($fechaActual);
        //         $mes = $meses[($fecha->format('n')) - 1];
        //         $fechaFormateada = $fecha->format('d') . ' de ' . $mes;
        //         array_push($dias, $fechaFormateada);
        //         array_push($conteos, $registro->conteoTotal);
        //         if($maximo < $registro->conteoTotal){
        //             $maximo = $registro->conteoTotal;
        //         }

        //     }



        //     return [
        //         'tipo' => 'COMPARATIVO',
        //         'conteoSeparado' => $personas,
        //         'registrosPorFechas' => [
        //             'conteos' => $conteos,
        //             'fechas' => $dias,
        //             'maximo' => $maximo,
        //         ],
        //     ];
        // }
        // else{
        //     //CUANDO ES AGRUPACIÓN, DESPLEGAR UNA GRAFICA DE BARRAS PARA COMPARAR LA SUMA DE TODOS LOS REGISTROS
        //     //CONTRA SUS METAS SUMADAS Y POBLACIONES SUMADAS
        //     //Y UNA GRAFICA DE PIE PARA VER EL PORCETANJE DE REGISTROS POR CADA SECCIÓN SELECCIONADA
        //     $personas = $queryPrincial->join('seccions', 'identificacions.seccion_id', '=', 'seccions.id')
        //     ->whereIn('seccion_id', $seccionesSeleccionadas)
        //     ->select('seccion_id', 'poblacion', 'objetivo', DB::raw('COUNT(*) as conteoTotal'))
        //     ->groupBy('seccion_id', 'poblacion', 'objetivo')
        //     ->get();

        //     $registrosPorFechas = $queryPrincial->whereIn('seccion_id', $seccionesSeleccionadas)
        //     ->select('fecha_registro', DB::raw('COUNT(*) as conteoTotal'))
        //     ->groupBy('fecha_registro')
        //     ->orderBy('fecha_registro', 'ASC')
        //     ->get();

        //     $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        //     $conteos = [];
        //     $dias = [];
        //     $maximo = 0;
        //     foreach ($registrosPorFechas as $registro) {
        //         $fechaActual = Carbon::parse($registro->fecha_registro)->format('d-F');
        //         $fecha = Carbon::parse($fechaActual);
        //         $mes = $meses[($fecha->format('n')) - 1];
        //         $fechaFormateada = $fecha->format('d') . ' de ' . $mes;
        //         array_push($dias, $fechaFormateada);
        //         array_push($conteos, $registro->conteoTotal);
        //         if($maximo < $registro->conteoTotal){
        //             $maximo = $registro->conteoTotal;
        //         }

        //     }



        //     return [
        //         'tipo' => 'AGRUPACION',
        //         'conteoSeparado' => $personas,
        //         'registrosPorFechas' => [
        //             'conteos' => $conteos,
        //             'fechas' => $dias,
        //             'maximo' => $maximo,
        //         ],
        //     ];
        // }

    }

    public function exportarMetas(){
        return Excel::download(new MetaExport, 'metas_secciones.xlsx');
    }

    public function importarMetas(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,csv,xls',
        ]);

        $data = Excel::toArray([], $request->file('archivo'))[0];

        if (count($data) < 1) {
            return back()->with('error', 'El archivo está vacío.');
        }

        $headers = array_map('trim', $data[0]);
        $required = ['sección', 'objetivos', 'votación total'];

        foreach ($required as $col) {
            if (!in_array($col, $headers)) {
                return back()->with('error', "Falta la columna requerida: {$col}");
            }
        }

        $idxSeccion = array_search('sección', $headers);
        $idxObjetivos = array_search('objetivos', $headers);
        $idxVotacion = array_search('votación total', $headers);

        $updates = [];
        for ($i = 1; $i < count($data); $i++) {
            $fila = $data[$i];
            $seccion = trim($fila[$idxSeccion] ?? '');
            $objetivo = trim($fila[$idxObjetivos] ?? '');
            $votacion = trim($fila[$idxVotacion] ?? '');

            if (empty($seccion)) break;

            $updates[$seccion] = [
                'objetivo' => is_numeric($objetivo) ? $objetivo : 0,
                'poblacion' => is_numeric($votacion) ? $votacion : 0,
            ];
        }

        if (empty($updates)) {
            return back()->with('error', 'No hay datos válidos para actualizar.');
        }

        // 🔥 Construir un UPDATE masivo con CASE
        $ids = implode(',', array_keys($updates));

        $sqlObjetivo = "CASE id ";
        $sqlPoblacion = "CASE id ";

        foreach ($updates as $id => $valores) {
            $sqlObjetivo .= "WHEN {$id} THEN {$valores['objetivo']} ";
            $sqlPoblacion .= "WHEN {$id} THEN {$valores['poblacion']} ";
        }

        $sqlObjetivo .= "END";
        $sqlPoblacion .= "END";

        $sql = "UPDATE seccions
                SET objetivo = {$sqlObjetivo},
                    poblacion = {$sqlPoblacion}
                WHERE id IN ({$ids})";

        DB::statement($sql);

        return back()->with('success', 'Importación completada correctamente.');
    }


    public function municipios($distritoFederalId)
    {
        $distritoFederalArray = explode(',', $distritoFederalId);
        $municipios = municipio::when(!in_array(0, $distritoFederalArray) || empty($distritoFederalArray), function ($query) use ($distritoFederalArray) {
            $query->whereIn('distrito_federal_id', $distritoFederalArray);
        })->orderBy('nombre')->get();
        return response()->json($municipios);
    }

    public function distritosLocales($municipioId)
    {
        $MunicipioArray = explode(',', $municipioId);
        $locales = distritoLocal::when(!in_array(0, $MunicipioArray) || empty($MunicipioArray), function ($query) use ($MunicipioArray) {
            $query->whereIn('municipio_id', $MunicipioArray);
        })->orderBy('id')->get();
        return response()->json($locales);
    }

    public function secciones($distritoLocalId)
    {
        $distritoLocalArray = explode(',', $distritoLocalId);
        $secciones = seccion::when(!in_array(0, $distritoLocalArray) || empty($distritoLocalArray), function ($query) use ($distritoLocalArray) {
            $query->whereIn('distrito_local_id', $distritoLocalArray);
        })->orderBy('id')->get();
        return response()->json($secciones);
    }
}
