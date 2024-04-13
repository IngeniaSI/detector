<?php

namespace App\Http\Controllers;

use App\Models\meta;
use App\Models\persona;
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
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fechaActual = Carbon::now();
        $conteos = [];
        $dias = [];
        $maximo = 0;
        $user = auth()->user();
        if($user->getRoleNames()->first() == 'SUPER ADMINISTRADOR' || $user->getRoleNames()->first() == 'ADMINISTRADOR'){
            for ($i = 13; $i >= 0; $i--) {
                $fecha = $fechaActual->copy()->subDays($i)->toDateString();
                $fechaFormateada = $fechaActual->copy()->subDays($i)->format('d-F');

                $fecha = Carbon::parse($fechaFormateada);
                $mes = $meses[($fecha->format('n')) - 1];
                $fechaFormateada = $fecha->format('d') . ' de ' . $mes;

                $conteo = persona::whereDate('created_at', $fecha)->count();
                array_push($conteos, $conteo);
                array_push($dias, $fechaFormateada);
                if($maximo < $conteo){
                    $maximo = $conteo;
                }
            }

            $conteoRegistrosPorDia = [
                'fechas' => $dias,
                'totales' => $conteos,
                'maximo' => $maximo
            ];
            $meta = meta::find(1);
            $numeroPersonas = persona::count();
            $numeroSimpatizantes = persona::where('simpatizante', 'SI')->count();

            return [
                [
                    $numeroPersonas,
                    $numeroSimpatizantes,
                    $meta->numeroObjetivo,
                    $meta->poblacionEstablecida
                ],
                $conteoRegistrosPorDia,
                [
                    $numeroPersonas,
                    $meta->poblacionEstablecida - $numeroPersonas
                ],
                [
                    $numeroSimpatizantes,
                    $meta->poblacionEstablecida - $numeroSimpatizantes
                ]
            ];
        }
        else{
            // $user = auth()->user();
            // // return $user;
            // $niveles = isset($user->niveles) ? explode( ',', $user->niveles) : null;
            // return $niveles; //APLICAR TRIM A CADA NIVEL
            for ($i = 13; $i >= 0; $i--) {
                $fecha = $fechaActual->copy()->subDays($i)->toDateString();
                $fechaFormateada = $fechaActual->copy()->subDays($i)->format('d-F');

                $fecha = Carbon::parse($fechaFormateada);
                $mes = $meses[($fecha->format('n')) - 1];
                $fechaFormateada = $fecha->format('d') . ' de ' . $mes;

                $conteo = persona::whereDate('created_at', $fecha)->count();
                array_push($conteos, $conteo);
                array_push($dias, $fechaFormateada);
                if($maximo < $conteo){
                    $maximo = $conteo;
                }
            }

            $conteoRegistrosPorDia = [
                'fechas' => $dias,
                'totales' => $conteos,
                'maximo' => $maximo
            ];
            $meta = meta::find(1);
            $numeroPersonas = persona::count();
            $numeroSimpatizantes = persona::where('simpatizante', 'SI')->count();

            return [
                [
                    $numeroPersonas,
                    $numeroSimpatizantes,
                    $meta->numeroObjetivo,
                    $meta->poblacionEstablecida
                ],
                $conteoRegistrosPorDia,
                [
                    $numeroPersonas,
                    $meta->poblacionEstablecida - $numeroPersonas
                ],
                [
                    $numeroSimpatizantes,
                    $meta->poblacionEstablecida - $numeroSimpatizantes
                ]
            ];
        }
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

    public function filtrar(){
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fechaActual = Carbon::now();
        $conteos = [];
        $dias = [];
        $maximo = 0;
        $user = auth()->user();
        if($user->getRoleNames()->first() == 'SUPER ADMINISTRADOR' || $user->getRoleNames()->first() == 'ADMINISTRADOR'){
            for ($i = 13; $i >= 0; $i--) {
                $fecha = $fechaActual->copy()->subDays($i)->toDateString();
                $fechaFormateada = $fechaActual->copy()->subDays($i)->format('d-F');

                $fecha = Carbon::parse($fechaFormateada);
                $mes = $meses[($fecha->format('n')) - 1];
                $fechaFormateada = $fecha->format('d') . ' de ' . $mes;

                $conteo = persona::whereDate('created_at', $fecha)->count();
                array_push($conteos, $conteo);
                array_push($dias, $fechaFormateada);
                if($maximo < $conteo){
                    $maximo = $conteo;
                }
            }

            $conteoRegistrosPorDia = [
                'fechas' => $dias,
                'totales' => $conteos,
                'maximo' => $maximo
            ];
            $meta = meta::find(1);
            $numeroPersonas = persona::count();
            $numeroSimpatizantes = persona::where('simpatizante', 'SI')->count();

            return [
                [
                    $numeroPersonas,
                    $numeroSimpatizantes,
                    $meta->numeroObjetivo,
                    $meta->poblacionEstablecida
                ],
                $conteoRegistrosPorDia,
                [
                    $numeroPersonas,
                    $meta->poblacionEstablecida - $numeroPersonas
                ],
                [
                    $numeroSimpatizantes,
                    $meta->poblacionEstablecida - $numeroSimpatizantes
                ]
            ];
        }
        else{
            // $user = auth()->user();
            // // return $user;
            // $niveles = isset($user->niveles) ? explode( ',', $user->niveles) : null;
            // return $niveles; //APLICAR TRIM A CADA NIVEL
            for ($i = 13; $i >= 0; $i--) {
                $fecha = $fechaActual->copy()->subDays($i)->toDateString();
                $fechaFormateada = $fechaActual->copy()->subDays($i)->format('d-F');

                $fecha = Carbon::parse($fechaFormateada);
                $mes = $meses[($fecha->format('n')) - 1];
                $fechaFormateada = $fecha->format('d') . ' de ' . $mes;

                $conteo = persona::whereDate('created_at', $fecha)->count();
                array_push($conteos, $conteo);
                array_push($dias, $fechaFormateada);
                if($maximo < $conteo){
                    $maximo = $conteo;
                }
            }

            $conteoRegistrosPorDia = [
                'fechas' => $dias,
                'totales' => $conteos,
                'maximo' => $maximo
            ];
            $meta = meta::find(1);
            $numeroPersonas = persona::count();
            $numeroSimpatizantes = persona::where('simpatizante', 'SI')->count();

            return [
                [
                    $numeroPersonas,
                    $numeroSimpatizantes,
                    $meta->numeroObjetivo,
                    $meta->poblacionEstablecida
                ],
                $conteoRegistrosPorDia,
                [
                    $numeroPersonas,
                    $meta->poblacionEstablecida - $numeroPersonas
                ],
                [
                    $numeroSimpatizantes,
                    $meta->poblacionEstablecida - $numeroSimpatizantes
                ]
            ];
        }
    }
}
