@extends('Pages.plantilla')

@section('tittle')
Tabla de Simpatizantes
@endsection

@section('cuerpo')
@if (session()->has('mensaje'))
        <script>
            alert('{{session("mensaje")}}');
        </script>
    @endif
    <br>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Tabla de Personas</h1>

        <div class="card mb-4">
            <div class="card-header">
            <center>
                <a href="{{route('agregarSimpatizante.index')}}">
                    <button class="btn btn-primary">Agregar Persona</button>
                </a>
            </center>
            </div>
            <div class="card-body">
                    {{-- TABLA DE USUARIOS --}}
                        <table id="tablaUsuarios" class="table table-striped table-bordered dt-responsive display" style="width:100%">
                            <thead>
                                <th>Fecha de Registro:</th>
                                <th>Supervisado:</th>
                                <th>Folio:</th>
                                <th>Nombre completo:</th>
                                <th>Genero:</th>
                                <th>Fecha de Nacimiento:</th>
                                <th>Rango de Edad:</th>
                                <th>Teléfono Celular:</th>
                                <th>Télefono Fijo:</th>
                                <th>Correo Electronico:</th>
                                <th>Facebook:</th>
                                <th>Calle:</th>
                                <th>Número Ext:</th>
                                <th>Número Int:</th>
                                <th>Colonia:</th>
                                <th>C.P:</th>
                                <th>Entidad Federativa:</th>
                                <th>Distrito Federal:</th>
                                <th>Municipio:</th>
                                <th>Distrito Local:</th>
                                <th>Afiliado:</th>
                                <th>Simpatizante:</th>
                                <th>Programa:</th>
                                <th>Funciones:</th>
                                <th>Etiquetas:</th>
                                <th>Observaciones:</th>
                                @can('crudSimpatizantes.verificar')
                                    <th>Opciones:</th>
                                @endcan
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        @error('errorBorrar')
                            <h5>{{$message}}</h5>
                        @enderror
            </div>
            </div>
            </div>
    </div>
    </div>
    </div>
    </div>
@endsection

@section('scripts')
<script src="//cdn.datatables.net/2.0.3/css/dataTables.dataTables.min.css"></script>
<script text="text/javascript">

    // FUNCION PARA CARGAR TABLA DE USUARIOS
    $(document).ready(function () {

        var table = $('#tablaUsuarios').DataTable( {
            lengthChange: true,
            responsive: true,
            language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json',
            },
            buttons: [ 'copy', 'excel', 'csv', 'pdf', 'colvis' ]
            } );

            table.buttons().container()
            .appendTo( '#example_wrapper .col-md-6:eq(0)' );
        $.when(
            $.ajax({
                type: "get",
                url: "{{route('crudSimpatizantes.inicializar')}}",
                data: [],
                contentType: "application/x-www-form-urlencoded",
                success: function (response) {
                    $.each(response, function (index, elemento) {
                        $('#tablaUsuarios').DataTable().row.add([
                            `<img src="{{ asset('Plantilla/assets/img/mas.png') }}" width="15px" height="15px" > ${elemento.fecha_registro}`,
                            (elemento.supervisado) ? 'Sí' : 'No', elemento.folio, `${elemento.nombres} ${elemento.apellido_paterno} ${elemento.apellido_materno}`,
                            elemento.genero, elemento.fecha_nacimiento, '18 - 99', elemento.telefono_celular,
                            elemento.telefono_fijo, elemento.correo, elemento.nombre_en_facebook, elemento.calle,
                            elemento.numero_exterior, elemento.numero_interior, elemento.nombreColonia, elemento.codigo_postal,
                            'entidadFederativa', 'distritoFederal', 'municipio', 'distritoLocal', elemento.afiliado,
                            elemento.simpatizante, elemento.programa,
                            elemento.funcion_en_campania, elemento.etiquetas, elemento.observaciones,
                            @can('crudSimpatizantes.verificar')
                                (elemento.supervisado) ?
                                    '<button class="btn btn-primary">Editar</button>'+
                                    `<form action="{{url('/')}}/simpatizantes/borrar-${elemento.id}" method="post">`+
                                        '<input type="hidden" name="_token" value="{{csrf_token()}}">'+
                                        '<button class="btn btn-danger">Borrar</button>'+
                                    '</form>'

                                :
                                    `<form action="{{url('/')}}/simpatizantes/supervisar-${elemento.id}" method="post">`+
                                        '<input type="hidden" name="_token" value="{{csrf_token()}}">'+
                                        '<button class="btn btn-success">Supervisado</button>'+
                                    '</form>'+
                                    '<button class="btn btn-primary">Editar</button>'+
                                    `<form action="{{url('/')}}/simpatizantes/borrar-${elemento.id}" method="post">`+
                                        '<input type="hidden" name="_token" value="{{csrf_token()}}">'+
                                        '<button class="btn btn-danger">Borrar</button>'+
                                    '</form>'

                            @endcan
                        ]).draw();
                    });
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
        });



    });

</script>
@endsection
