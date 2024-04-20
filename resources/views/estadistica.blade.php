@extends('Pages.plantilla')

@section('tittle')
   Estadística
@endsection

@section('cuerpo')

<style>
    .select2-container .select2-selection--multiple {

    /* width: 120px; */
}
</style>
@can('estadistica.cambiarMeta')
    <!-- Modal Agregar Usuario -->
    <div class="modal fade" id="cargarMeta" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            {{-- FORMULARIO DE CAMBIAR META --}}
            <form id="formularioCargarMeta" action="{{route('estadistica.cargarMeta')}}" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Cargar meta</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="col">
                                <h4>Sección objetivo:</h4>
                                <select name="seccionObjetivo" id="seccionObjetivo" class="form-select selectToo" style="width: 100%">
                                    <option value="">001</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <h4>Población:</h4>
                                <input type="number" id="cantidadObjetivo" name="cantidadObjetivo" class="form-control" value="{{old('cantidadObjetivo')}}" minlength="2" maxlength="255">
                                @error('cantidadObjetivo')
                                    <div class="mensajesErrores p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                @enderror
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col">
                                <h4>Listado Nominal:</h4>
                                <input type="number" id="poblacion" name="poblacion" value="{{old('poblacion')}}" class="form-control" minlength="2" maxlength="255">
                                @error('poblacion')
                                    <div class="mensajesErrores p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary">Guardar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endcan
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between">
        <h1 class="mt-4">Estadística</h1>
        @can('estadistica.cambiarMeta')
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cargarMeta">Cargar Meta </button>
        @endcan
    </div>
    <div class="row">
        <div class="col">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-calendar me-1"></i>
                    Fecha de Inicio
                </div>
                <div class="card-body">
                    <input class="form-control" type="date"  />
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-calendar me-1"></i>
                    Fecha de Final
                </div>
                <div class="card-body justify-content">
                    <center>
                        <input class="form-control" type="date"/>
                    </center>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card ">
                <div class="card-header">
                    <i class="fas fa-calendar me-1"></i>
                    Sección
                </div>
                <div class="card-body justify-content">
                    <center>
                        <select id="tipoSeleccion" class="form-select" name="tipoSeleccion">
                            <option value="COMPARATIVO" >Todas las secciones</option>
                            <option value="AGRUPAR">Agrupar...</option>
                        </select>
                    </center>
                </div>
            </div>
        </div>
        <div class="col" id="PorSeparado" style="display:none;">
            <div class="card ">
                <div class="card-header">
                    <i class="fas fa-calendar me-1"></i>
                    Selecciona la Sección
                </div>
                <div class="card-body justify-content" style="max-height:100px; overflow:auto;">
                    <center>
                        <select id="seccionarAgrupar" class="form-select selectToo" style="width:100%;" name="seccionesAgrupadas[]" multiple="multiple">

                        </select>
                    </center>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card mb-2">
                <div class="card-header">
                    <i class="fas fa-search me-1"></i>
                    Base de Datos
                </div>
                <div class="card-body">
                    <input class="btn btn-primary btn-block" type="button" value="Consultar" />
                </div>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Sección
                </div>
                <div class="card-body"><canvas id="seccionChart" width="100%" height="50"></canvas></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Conteo total
                </div>
                <div class="card-body"><canvas id="conteoTotalChart" width="100%" height="50"></canvas></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-area me-1"></i>
                    Registros por Semanas
                </div>
                <div class="card-body"><canvas id="registrosPorSemana" width="100%" height="50"></canvas></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>
                    Registros con respecto a población
                </div>
                <div class="card-body"><canvas id="registrosContraPoblacion" width="100%" height="50"></canvas></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>
                    Registros con respecto a objetivo
                </div>
                <div class="card-body"><canvas id="simpatizantesContraObjetivo" width="100%" height="50"></canvas></div>
            </div>
        </div>
    </div>




</div>
@endsection

@section('scripts')
        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.6.2/chart.min.js"></script> --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.0.0/chartjs-plugin-datalabels.min.js"></script> --}}
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.6.0"></script>
        <link href="vendor/select2/dist/css/select2.min.css" rel="stylesheet" />
        <script src="vendor/select2/dist/js/select2.min.js"></script>
        {{-- <script src="{{ asset('Plantilla/assets/demo/chart-area-demo.js')}}"></script>
        <script src="{{ asset('Plantilla/assets/demo/chart-bar-demo.js')}}"></script>
        <script src="{{ asset('Plantilla/assets/demo/chart-pie-demo.js')}}"></script> --}}
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script text="text/javascript">
            var configurarSecciones;
            $('#tipoSeleccion').on('change', function() {
                if( this.value == 'AGRUPAR'){
                    $('#PorSeparado').show();
                }else{
                    $('#PorSeparado').hide();
                }
            });
            $(document).ready(function () {
                $('#PorSeparado').hide();
                $('.selectToo').select2();
                $.when(
                $.ajax({
                    type: "get",
                    url: "{{route('estadistica.inicializar')}}",
                    data: [],
                    contentType: "application/x-www-form-urlencoded",
                    success: function (response) {
                        configurarSecciones = response.seccionesConfigurarMetas;
                        $.each(response.seccionesAccesibles, function (indexInArray, valueOfElement) {
                            $('#seccionarAgrupar').append($('<option>').html(valueOfElement))
                        });

                        // var options = {
                        //     tooltips: {
                        //         enabled: false
                        //     },
                        //     plugins: {
                        //         datalabels: {
                        //             formatter: (value, ctx) => {
                        //                 const datapoints = ctx.chart.data.datasets[0].data
                        //                 const total = datapoints.reduce((total, datapoint) => total + datapoint, 0)
                        //                 const percentage = value / total * 100
                        //                 return percentage.toFixed(2) + "%";
                        //             },
                        //             color: '#000',
                        //             backgroundColor: '#fff',
                        //             borderWidth: '1',
                        //             borderColor: '#aaa',
                        //             borderRadius: '5'
                        //         }
                        //     }
                        // };

                        // var ctx1 = document.getElementById("registrosContraPoblacion").getContext('2d');
                        // var myChart1 = new Chart(ctx1, {
                        //     type: 'pie',
                        //     data: {
                        //         labels: [
                        //             'Registros Hechos',
                        //             'Población Faltante',
                        //         ],
                        //         datasets: [{
                        //             data: response[2],
                        //             backgroundColor: [
                        //                 "#0070FF",
                        //                 "#00ACCC",
                        //             ],
                        //             borderColor: "#fff"
                        //         }]
                        //     },
                        //     options: options,
                        //     plugins: [ChartDataLabels],
                        // });

                        // var ctx2 = document.getElementById("simpatizantesContraObjetivo").getContext('2d');
                        // var myPieChart2 = new Chart(ctx2, {
                        //     type: 'pie',
                        //     data: {
                        //         labels: [
                        //             "Simpatizantes Hechos",
                        //             "Posibles Simpatizantes",
                        //         ],
                        //         datasets: [{
                        //             data: response[3],
                        //             backgroundColor: [
                        //                 "#0070FF",
                        //                 "#00ACCC",
                        //             ],
                        //             borderColor: "#fff"
                        //         }],
                        //     },
                        //     options: options,
                        //     plugins: [ChartDataLabels],
                        // });

                        // var ctx = document.getElementById("registrosPorSemana");
                        // var myLineChart = new Chart(ctx, {
                        //     type: 'line',
                        //     data: {
                        //         labels: response[1].fechas,
                        //         datasets: [{
                        //             label: "Cantidad",
                        //             lineTension: 0.3,
                        //             backgroundColor: "rgba(2,117,216,0.2)",
                        //             borderColor: "rgba(2,117,216,1)",
                        //             pointRadius: 5,
                        //             pointBackgroundColor: "rgba(2,117,216,1)",
                        //             pointBorderColor: "rgba(255,255,255,0.8)",
                        //             pointHoverRadius: 5,
                        //             pointHoverBackgroundColor: "rgba(2,117,216,1)",
                        //             pointHitRadius: 50,
                        //             pointBorderWidth: 2,
                        //             data: response[1].totales,
                        //         }],
                        //     },
                        //     options: {
                        //         scales: {
                        //             xAxes: [{
                        //                 time: {
                        //                     unit: 'date'
                        //                 },
                        //                 gridLines: {
                        //                     display: false
                        //                 },
                        //                 ticks: {
                        //                     maxTicksLimit: 14
                        //                 }
                        //             }],
                        //             yAxes: [{
                        //                 ticks: {
                        //                     min: 0,
                        //                     max: response[1].maximo,
                        //                     maxTicksLimit: 5
                        //                 },
                        //                 gridLines: {
                        //                     color: "rgba(0, 0, 0, .125)",
                        //                 }
                        //             }],
                        //         },
                        //         legend: {
                        //             display: false
                        //         },
                        //         plugins: {
                        //             datalabels: {
                        //                 formatter: (value, ctx) => {
                        //                     const datapoints = ctx.chart.data.datasets[0].data
                        //                     const total = datapoints.reduce((total, datapoint) => total + datapoint, 0)
                        //                     const percentage = value / total * 100
                        //                     return '';
                        //                 },
                        //                 color: '#fff',
                        //             }
                        //         }
                        //     },
                        // });

                        // var ctx3 = document.getElementById("conteoTotalChart");
                        // var myLineChart = new Chart(ctx3, {
                        //     type: 'bar',
                        //     data: {
                        //         labels: [
                        //             "Registros Hechos",
                        //             "Simpatizantes Hechos",
                        //             "Simpatizantes Objetivos",
                        //             "Total Población",
                        //             ],
                        //         datasets: [{
                        //             label: "Revenue",
                        //             backgroundColor: "rgba(2,117,216,1)",
                        //             borderColor: "rgba(2,117,216,1)",
                        //             data: response[0],
                        //         }],
                        //     },
                        //     options: {
                        //         scales: {
                        //         xAxes: [{
                        //             time: {
                        //                 unit: 'month'
                        //             },
                        //             gridLines: {
                        //                 display: false
                        //             },
                        //             ticks: {
                        //                 maxTicksLimit: 4
                        //             }
                        //         }],
                        //         yAxes: [{
                        //             ticks: {
                        //                 min: 0,
                        //                 max: response[0][3] * 1.05 ,
                        //                 maxTicksLimit: 5
                        //             },
                        //             gridLines: {
                        //                 display: true
                        //             }
                        //         }],
                        //         },
                        //         legend: {
                        //             display: false
                        //         },
                        //         plugins: {
                        //             datalabels: {
                        //                 formatter: (value, ctx) => {
                        //                     const datapoints = ctx.chart.data.datasets[0].data
                        //                     const total = datapoints.reduce((total, datapoint) => total + datapoint, 0)
                        //                     const percentage = value / total * 100
                        //                     return percentage.toFixed(2) + "%";
                        //                 },
                        //                 color: '#000',
                        //                 backgroundColor: '#fff',
                        //                 borderWidth: '1',
                        //                 borderColor: '#aaa',
                        //                 borderRadius: '5'
                        //             }
                        //         }
                        //     },
                        //     plugins: [ChartDataLabels],

                        // });
                    },
                    error: function( data, textStatus, jqXHR){
                        if (jqXHR.status === 0) {
                            console.log('Not connect: Verify Network.');
                        } else if (jqXHR.status == 404) {
                            console.log('Requested page not found [404]');
                        } else if (jqXHR.status == 500) {
                            console.log('Internal Server Error [500].');
                        } else if (textStatus === 'parsererror') {
                            console.log('Requested JSON parse failed.');
                        } else if (textStatus === 'timeout') {
                            console.log('Time out error.');
                        } else if (textStatus === 'abort') {
                            console.log('Ajax request aborted.');
                        } else {
                            console.log('Uncaught Error: ' + jqXHR.responseText);
                        }
                    }
                })
                ).then(
                    function( data, textStatus, jqXHR ) {
                        $('.selectToo').select2({
                        language: {

                            noResults: function() {

                            return "No hay resultado";
                            },
                            searching: function() {

                            return "Buscando..";
                            }
                        }
                    });
                });
            });

        </script>
@endsection
