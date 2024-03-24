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
                                <th>Delegación:</th>
                                <th>C.P:</th>
                                <th>Coordenadas:</th>
                                <th>Estidad Federativa:</th>
                                <th>Distrito Federal:</th>
                                <th>Municipio:</th>
                                <th>Distrito Local:</th>
                                <th>Afiliado:</th>
                                <th>Simpatizante:</th>
                                <th>Programa:</th>
                                <th>Funciones:</th>
                                <th>Etiquetas:</th>
                                <th>Observaciones:</th>
                                <th>Opciones:</th>
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

            $('#tablaUsuarios').DataTable().row.add([
                        '<img src="{{ asset('Plantilla/assets/img/mas.png') }}" width="15px" height="15px" >03-05-2024 05:10:58	','Prueba','Prueba','Prueba','Prueba','Prueba','Prueba','Prueba','Prueba','Prueba',
                        'Prueba','Prueba','Prueba','Prueba','Prueba','Prueba','Prueba','Prueba','Prueba','Prueba',
                        'Prueba','Prueba','Prueba','Prueba','Prueba','Prueba','Prueba','<button class="btn btn-primary">Editar</button>'
                    ]).draw();
        $.when(
            $.ajax({
                type: "get",
                url: "{{route('crudSimpatizantes.inicializar')}}",
                data: [],
                contentType: "application/x-www-form-urlencoded",
                success: function (response) {
                    if(response.length == 0){
                        // var nuevaFila = $('<tr>').append(
                        //     $('<td>').attr('colspan', 99).text('Sin registros')
                        // );
                        // $('#tablaUsuarios tbody').append(nuevaFila);
                    }
                    $.each(response, function (index, elemento) {
                        // var nuevaFila = $('<tr>').append(
                        //     $('<td>').text(elemento.nombres + ' ' + elemento.apellido_paterno + ' ' + elemento.apellido_materno),
                        //     $('<td>').text(elemento.correo),
                        //     $('<td>').text(elemento.telefono_celular)
                        // );
                        // $('#tablaUsuarios tbody').append(nuevaFila);
                    // $('#tablaUsuarios').DataTable().row.add([
                    //     elemento.nombres + ' ' + elemento.apellido_paterno + ' ' + elemento.apellido_materno,
                    //     elemento.correo,
                    //     elemento.telefono_celular
                    // ]).draw();
                    
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
