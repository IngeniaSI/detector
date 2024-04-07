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
                <div class="d-flex justify-content-end">
                    <a href="{{route('crudSimpatizantes.descargar')}}" target="_blank" class="me-3">
                        <button class="btn btn-primary">Exportar a Excel</button>
                    </a>
                    <a href="{{route('agregarSimpatizante.index')}}">
                        <button class="btn btn-primary">Agregar Persona</button>
                    </a>
                </div>
            </div>
            <div class="card-body">
                {{-- TABLA DE USUARIOS --}}
                <table id="tablaUsuarios" class="table table-striped table-bordered dt-responsive display" style="width:100%">
                    <thead>
                        <th>Id</th>
                        <th>Folio</th>
                        <th>Nombre Completo</th>
                        <th>Sección</th>
                        <th>Distrito Local</th>
                        <th>Municipio</th>
                        <th>Distrito Federal</th>
                        <th>Entidad</th>
                        @can('crudSimpatizantes.verificar')
                            <th>Opciones:</th>
                        @endcan
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                @error('errorBorrar')
                <div class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                @enderror
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
                            elemento.personaId, elemento.folio, `${elemento.nombres} ${elemento.apellido_paterno} ${elemento.apellido_materno}`,
                            elemento.seccionId, elemento.distritoLocalId, elemento.nombreMunicipio, elemento.distritoFederalId, elemento.nombreEntidad,
                            @can('crudSimpatizantes.verificar')
                                (elemento.supervisado) ?
                                    `<a href="{{url('/')}}/simpatizantes/modificar-${elemento.personaId}">`+
                                        `<button class="btn btn-primary">Editar</button>`+
                                    `</a>`+
                                    `<form action="{{url('/')}}/simpatizantes/borrar-${elemento.personaId}" method="post">`+
                                        '<input type="hidden" name="_token" value="{{csrf_token()}}">'+
                                        '<button class="btn btn-danger">Borrar</button>'+
                                    '</form>'
                                :
                                    `<form action="{{url('/')}}/simpatizantes/supervisar-${elemento.personaId}" method="post">`+
                                        '<input type="hidden" name="_token" value="{{csrf_token()}}">'+
                                        '<button class="btn btn-success">Supervisado</button>'+
                                    '</form>'+
                                    `<a href="{{url('/')}}/simpatizantes/modificar-${elemento.personaId}">`+
                                        `<button class="btn btn-primary">Editar</button>`+
                                    `</a>`+
                                    `<form action="{{url('/')}}/simpatizantes/borrar-${elemento.personaId}" method="post">`+
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
