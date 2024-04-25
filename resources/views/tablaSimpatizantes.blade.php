@extends('Pages.plantilla')

@section('tittle')
Tabla de Simpatizantes
@endsection

@section('cuerpo')
    <style>
        /* Estilo para el enlace deshabilitado */
        .disabled {
            pointer-events: none; /* Evita que el enlace sea clickeable */
            opacity: 0.5; /* Aplica opacidad para indicar visualmente que está deshabilitado */
            cursor: not-allowed; /* Cambia el cursor a 'no permitido' */
        }
    </style>
    @if (session()->has('mensaje'))
        <script>
            alert('{{session("mensaje")}}');
        </script>
    @endif
    <br>

    <div class="container-fluid px-4">
        <h1 class="mt-4">Tabla de Personas</h1>
        <div class="row">
            <div class="col">
                <h3>Registros sin supervisar: <span id="conteoSinSupervisar"></span></h3>
            </div>
            <div class="col">
                <h3>Personas Registradas: <span id="conteoTotal"></span></h3>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-end">
                    @can('crudSimpatizantes.exportar')
                        <a href="{{route('crudSimpatizantes.descargar')}}" target="_blank" class="me-3">
                            <button class="btn btn-primary">Exportar a Excel</button>
                        </a>
                    @endcan
                    @can('agregarSimpatizante.index')
                        <a href="{{route('agregarSimpatizante.index')}}">
                            <button class="btn btn-primary">Agregar Persona</button>
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                {{-- TABLA DE USUARIOS --}}
                <table id="tablaUsuarios" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <th>Consecutivo</th>
                        <th>Estatus</th>
                        <th>Nombre Completo</th>
                        <th>Telefono</th>
                        <th>Sección</th>
                        <th>Distrito Local</th>
                        <th>Opciones:</th>
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
    @if (session()->has('personaModificarDenegada'))
    Swal.fire({
            'title':"Error",
            'text':"{{session('personaModificarDenegada')}}",
            'icon':"error"
        });
    @endif
    // FUNCION PARA CARGAR TABLA DE USUARIOS
    $(document).ready(function () {
        var table = $('#tablaUsuarios').DataTable( {
            @if($user->getRoleNames()->first() == 'CAPTURISTA')
                order: [[1, 'asc'], [0, 'desc']],
            @else
                order: [[1, 'desc'], [0, 'desc']],
            @endif
            scrollX: true,
            lengthChange: true,
            // responsive: true,
            language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json',
            },
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "{{route('crudSimpatizantes.inicializar')}}",
            },
            columns: [
                { data: 'id' },
                { data: null,
                    render: function(data, type, row){
                        if(data.supervisado){
                            estatus = '<div class="bg-success bg-gradient text-white fw-bold rounded p-3">Supervisado</div>';
                        }
                        else{
                            estatus = '<div class="bg-danger bg-gradient text-white fw-bold rounded p-3"> No Supervisado </div>';
                        }
                        return estatus;
                    }},
                { data: 'nombre_completo'},
                { data: 'telefono_celular' },
                { data: 'seccionId' },
                { data: 'distritoLocalId' },
                { data: null,
                    render: function(data, type, row){
                        var botones = '';
                        @can('crudSimpatizantes.consultar')
                            botones += `<a href="{{url('/')}}/simpatizantes/consultar-${data.id}">`+
                                `<button class="btn btn-primary">Ver</button>`+
                            `</a>`;
                        @endcan
                        @can('crudSimpatizantes.modificar')
                            botones += (data.supervisado) ?
                                @can('crudSimpatizantes.verificar')
                                    `<form action="{{url('/')}}/simpatizantes/supervisar-${data.id}" id="formularioSupervisar" method="post">`+
                                        '<input type="hidden" name="_token" value="{{csrf_token()}}">'+
                                        '<button id="botonSubmitSupervisar" class="btn btn-danger">Cancelar Supervisado</button>'+
                                    '</form>'+
                                @endcan
                                `<a href="{{url('/')}}/simpatizantes/modificar-${data.id}" class="{{(auth()->user()->getRoleNames()->first() == 'CAPTURISTA') ? 'disabled' : '' }}">`+
                                    `<button class="btn btn-primary">Editar</button>`+
                                `</a>`
                                @can('crudSimpatizantes.verificar')
                                    +`<form action="{{url('/')}}/simpatizantes/borrar-${data.id}" method="post">`+
                                        '<input type="hidden" name="_token" value="{{csrf_token()}}">'+
                                        '<button class="btn btn-danger">Borrar</button>'+
                                    '</form>'
                                @endcan
                            :
                                @can('crudSimpatizantes.verificar')
                                    `<form action="{{url('/')}}/simpatizantes/supervisar-${data.id}" id="formularioSupervisar" method="post">`+
                                        '<input type="hidden" name="_token" value="{{csrf_token()}}">'+
                                        '<button id="botonSubmitSupervisar" class="btn btn-success">Supervisar</button>'+
                                    '</form>'+
                                @endcan
                                `<a href="{{url('/')}}/simpatizantes/modificar-${data.id}">`+
                                    `<button class="btn btn-primary">Editar</button>`+
                                `</a>`+
                                @can('crudSimpatizantes.verificar')
                                    `<form action="{{url('/')}}/simpatizantes/borrar-${data.id}" method="post">`+
                                        '<input type="hidden" name="_token" value="{{csrf_token()}}">'+
                                        '<button class="btn btn-danger">Borrar</button>'+
                                    '</form>'+
                                @endcan
                        @endcan
                        '';
                        return botones;
                    }},
                // Agrega más columnas según tus datos
            ]
            // buttons: [ 'copy', 'excel', 'csv', 'pdf', 'colvis' ]
        } );

        // table.buttons().container()
        // .appendTo( '#example_wrapper .col-md-6:eq(0)' );
        // Swal.fire({
        //     title: 'Cargando...',
        //     allowOutsideClick: false,
        //     showConfirmButton: false,
        //     html: '<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>'
        // });
        $.when(
                $.ajax({
                    type: "get",
                    url: "{{route('crudSimpatizantes.numeroSupervisados')}}",
                    data: [],
                    contentType: "application/x-www-form-urlencoded",
                    success: function (response) {
                        $('#conteoSinSupervisar').text(response.sinSupervisar);
                        $('#conteoTotal').text(response.total);
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
                // Swal.close();
        });


    });


</script>
@endsection
