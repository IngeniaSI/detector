<?php

namespace App\Http\Controllers;

use App\Exports\estructuraAfiliadosExport;
use App\Exports\listadoPersonasExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class reportesController extends Controller
{
    public function index()
    {
        return view('generarReportes');
    }

    public function generarReporte1(Request $request)
    {
        $fechaInicio = $request->input('inicio');
        $fechaFin = $request->input('fin');

        return Excel::download(new listadoPersonasExport($fechaInicio, $fechaFin), 'reporte_personas.xlsx');
    }
    public function generarReporte2()
    {
        return Excel::download(new estructuraAfiliadosExport(), 'estructura_operativa.xlsx');
    }
}
