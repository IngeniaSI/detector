@extends('Pages.plantilla')

@section('tittle')
   Generador de Reportes
@endsection

@section('cuerpo')
@php
    $anioActual = date('Y');
    $fechaInicioDefault = "$anioActual-01-01";
    $fechaFinDefault = date('Y-m-d');
@endphp
   <div class="container">
        <h2 class="mb-5 text-center">📊 Generador de Reportes en Excel</h2>

        {{-- ================================
             Reporte 1
        ================================= --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Reporte 1: Personas Registradas</h5>
                <p class="card-text">Descarga un listado completo de las personas registradas en el sistema.</p>

                <div class="row g-3 align-items-end mb-3">
                    <div class="col-md-4">
                        <label for="fechaInicio1" class="form-label">Desde:</label>
                        <input type="date" id="fechaInicio1" class="form-control" value="{{ $fechaInicioDefault }}">
                    </div>
                    <div class="col-md-4">
                        <label for="fechaFin1" class="form-label">Hasta:</label>
                        <input type="date" id="fechaFin1" class="form-control" value="{{ $fechaFinDefault }}">
                    </div>
                    <div class="col-md-4 d-flex">
                        <button id="btnReporte1" class="btn btn-success w-100">
                            <i class="bi bi-file-earmark-excel"></i> Descargar Excel
                        </button>
                    </div>
                </div>
                </div>
        </div>

        {{-- ================================
             Reporte 2
        ================================= --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Reporte 2: Listado Estructura operativa</h5>
                <p class="card-text">Listado de coordinadores estatales, locales y de sección del partido.</p>

                <a href="{{route('reportes.generarReporte2')}}" target="_blank" class="btn btn-success">
                    <i class="bi bi-file-earmark-excel"></i> Descargar Excel
                </a>
            </div>
        </div>

         {{-- ================================
             Reporte 3
        ================================= --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Reporte 3: Listado de Metas</h5>
                <p class="card-text">Listado de metas establecidas y su cumplimiento.</p>

                <a href="{{route('estadistica.exportarMetas')}}" target="_blank" class="btn btn-success">
                    <i class="bi bi-file-earmark-excel"></i> Descargar Excel
                </a>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        $('#btnReporte1').click(function() {
            let inicio = $('#fechaInicio1').val();
            let fin = $('#fechaFin1').val();
            window.open(`/detector/reportes/generar-reporte-1?inicio=${inicio}&fin=${fin}`, '_blank');
        });
    </script>
@endsection

