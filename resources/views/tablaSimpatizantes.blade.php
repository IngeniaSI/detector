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
                        <table id="tablaUsuarios" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                            <thead>
                                <th>Nombre completo</th>
                                <th>Correo</th>
                                <th>Telefono celular</th>
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

    <
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="{{ asset('Plantilla/js/scripts.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
        <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.bootstrap4.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.colVis.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap4.min.js"></script>
<script text="text/javascript">

    // FUNCION PARA CARGAR TABLA DE USUARIOS
    $(document).ready(function () {

        var table = $('#tablaUsuarios').DataTable( {
            lengthChange: true,
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
                    if(response.length == 0){
                        var nuevaFila = $('<tr>').append(
                            $('<td>').attr('colspan', 99).text('Sin registros')
                        );
                        $('#tablaUsuarios tbody').append(nuevaFila);
                    }
                    $.each(response, function (index, elemento) {
                        // var nuevaFila = $('<tr>').append(
                        //     $('<td>').text(elemento.nombres + ' ' + elemento.apellido_paterno + ' ' + elemento.apellido_materno),
                        //     $('<td>').text(elemento.correo),
                        //     $('<td>').text(elemento.telefono_celular)
                        // );
                        // $('#tablaUsuarios tbody').append(nuevaFila);
                        $('#tablaUsuarios').DataTable().row.add([
                            elemento.nombres + ' ' + elemento.apellido_paterno + ' ' + elemento.apellido_materno,
                            elemento.correo,
                            elemento.telefono_celular
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
