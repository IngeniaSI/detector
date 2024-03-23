@extends('Pages.plantilla')

@section('tittle')
   Estadística
@endsection

@section('cuerpo')
<div class="container-fluid px-4">
                        <h1 class="mt-4">Estadística</h1>
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
                                        <center> <input class="form-control" type="date"  /></center>
                                       
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
                        


                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-chart-area me-1"></i>
                                Registros por Día
                            </div>
                            <div class="card-body"><canvas id="myAreaChart" width="100%" height="30"></canvas></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-bar me-1"></i>
                                        Registros por Municipio
                                    </div>
                                    <div class="card-body"><canvas id="myBarChart" width="100%" height="50"></canvas></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-pie me-1"></i>
                                        Registro por Rango de Edades
                                    </div>
                                    <div class="card-body"><canvas id="myPieChart" width="100%" height="50"></canvas></div>
                                </div>
                            </div>
                        </div>
                    </div>


@endsection

@section('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="{{ asset('Plantilla/assets/demo/chart-area-demo.js')}}"></script>
        <script src="{{ asset('Plantilla/assets/demo/chart-bar-demo.js')}}"></script>
        <script src="{{ asset('Plantilla/assets/demo/chart-pie-demo.js')}}"></script>
@endsection
