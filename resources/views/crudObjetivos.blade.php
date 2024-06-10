@extends('Pages.plantilla')

@section('tittle')
    Objetivos
@endsection


@section('cuerpo')
    <style>
        .select2-container--open {
            z-index: 9999999
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/tutorials/timelines/timeline-1/assets/css/timeline-1.css">



    <!-- Modal Agregar oportuinidad -->
    <div class="modal fade" id="modalNuevoObjetivo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            {{-- FORMULARIO DE AGREGAR oportuinidad --}}
            <form id="formularioAgregarObjetivo" action="{{route('objetivos.agregar')}}" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"> Crear nuevo objetivo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="col">
                                <h4>Nombre:</h4>
                                <input id="nombreObjetivo" name="nombreObjetivo" type="text" class="form-control">
                                <h4>Descripción:</h4>
                                <textarea name="descripcionObjetivo" class="form-control" id="descripcionObjetivo" cols="30" rows="5"></textarea>
                                <h4>Etapas del objetivo:</h4>
                                <input id="etapasObjetivo" name="etapasObjetivo" type="text" class="form-control">
                                <small>*Escriba las estapas separadas por comas</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="botonCrearObjetivo" class="btn btn-primary">Crear</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal modificar oportuinidad -->
    <div class="modal fade" id="modalModificarObjetivo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            {{-- FORMULARIO DE AGREGAR oportuinidad --}}
            <form id="formularioModificarObjetivo" action="#" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"> Modificar objetivo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="col">
                                <h4>Nombre:</h4>
                                <input id="nombreObjetivoModificar" name="nombreObjetivo" type="text" class="form-control">
                                <h4>Descripción:</h4>
                                <textarea name="descripcionObjetivo" id="descripcionObjetivoModificar" class="form-control" cols="30" rows="5"></textarea>
                                <h4>Etapas del objetivo:</h4>
                                <input id="etapasObjetivoModificar" name="etapasObjetivo" type="text" class="form-control">
                                <small>*Escriba las estapas separadas por comas</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary">Modificar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Objetivos</h1>
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-end">
                        <button class="btn btn btn-success" data-bs-toggle="modal" data-bs-target="#modalNuevoObjetivo"><i class="fas fa-plus me-1"></i> Crear objetivo</button>
                </div>
            </div>
            <div class="card-body">

                <table id="tabla" class="table table-striped table-bordered " style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre Objetivo</th>
                            <th>Descripción</th>
                            <th>Numero de etapas</th>
                            <th>Estatus</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="/js/generador-contrasenias.js" text="text/javascript"></script>
    <script src="https://formbuilder.online/assets/js/form-builder.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.4.0/highlight.min.js"></script>
    <script src="https://formbuilder.online/assets/js/form-render.min.js"></script>


    <script text="text/javascript">
        var fbEditor, formBuilder, fbEditor2, formBuilder2,fbEditorPrevio, formBuilderPrevio;
        var encuestaACompartir = 0;
        var table;
        var promotorParaExportar = 0;
        var estatusParaExportar = "";
        function efectoCargando(){
            Swal.fire({
                title: 'Cargando...',
                allowOutsideClick: false,
                showConfirmButton: false,
                html: '<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>'
            });
        }

        $(document).ready(function () {
            // efectoCargando();

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
            table = $('#tabla').DataTable({
                order: [[0, 'desc']],
                scrollX: true,
                lengthChange: true,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json',
                },
                "processing": true,
                "serverSide": true,
                ajax: {
                    url: "{{route('objetivos.cargarTabla')}}"
                },
                columns: [
                    { data: 'id' },
                    { data: 'nombre'},
                    { data: 'descripcion'},
                    { data: 'numeroPasos'},
                    { data: 'estatus'},
                    { data: null,
                        render: function(data, type, row){
                            var botones = '';
                            var creando =
                                    '<button id="btnModificarEncuesta_'+data.id+'" onclick="cargarObjetivo('+data.id+')" class ="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalModificarObjetivo" >'+
                                        '<i class="fas fa-edit me-1">'+
                                    '</i>&nbsp;Editar'+
                                    '</button>'+
                                    '<form id="formularioIniciarEncuesta" onsubmit="efectoCargando()" action="{{url('/')}}/objetivos/cambiar-estatus-' + data.id + '" method="post">'+
                                        '<input type="hidden" value="{{csrf_token()}}" name="_token">'+
                                        '<button class ="btn btn-success">'+
                                            '<i class="fas fa-play">'+
                                        '</i>&nbsp;Activar objetivo'+
                                        '</button>'+
                                    '</form>'+
                                    '<form id="formularioBorrar_'+data.id+'" action="{{url('/')}}/objetivos/borrar-' + data.id + '" method="post">'+
                                        '<input type="hidden" value="{{csrf_token()}}" name="_token">'+
                                        `<button id="borrarUsuario_${data.id}" type="button" onclick="clickBorrar('${data.estatus}', '${data.id}')" class="btn btn btn-danger">`+
                                            '<i class="fas fa-close me-1">'+
                                        '</i>Borrar</button>'+
                                    '</form>';
                            var enCurso =
                                    '<form id="formularioCerrarEncuesta" onsubmit="efectoCargando()" action="{{url('/')}}/objetivos/cambiar-estatus-' + data.id + '" method="post">'+
                                        '<input type="hidden" value="{{csrf_token()}}" name="_token">'+
                                        '<button class ="btn btn-secondary">'+
                                            '<i class="fas fa-stop">'+
                                        '</i>&nbsp;Desactivar objetivo'+
                                        '</button>'+
                                    '</form>';

                            if(data.estatus == 'DESACTIVADO'){
                                botones += creando;
                            }
                            else if(data.estatus == 'ACTIVADO'){
                                botones += enCurso;
                            }
                            return botones;
                        }},
                ]
            });
        });

        $('#formularioAgregarObjetivo').submit(function (e) {
            efectoCargando();
            let nombreObjetivo = $('#nombreObjetivo').val();
            let descripcionObjetivo = $('#descripcionObjetivo').val();
            let etapasObjetivos = $('#etapasObjetivo').val();
            if(nombreObjetivo == 0 || descripcionObjetivo == 0 || etapasObjetivos == 0){
                Swal.fire({
                    'title':"Error",
                    'text':"No se pudo crear el objetivo. verifique el nombre, la descripcion o las etapas ingresadas.",
                    'icon':"error"
                });
                return false;
            }
        });

        $('#formularioModificarObjetivo').submit(function (e) {
            efectoCargando();
            let nombreObjetivo = $('#nombreObjetivoModificar').val();
            let descripcionObjetivo = $('#descripcionObjetivoModificar').val();
            let etapasObjetivos = $('#etapasObjetivoModificar').val();
            if(nombreObjetivo == 0 || descripcionObjetivo == 0 || etapasObjetivos == 0){
                Swal.fire({
                    'title':"Error",
                    'text':"No se pudo modificar el objetivo. verifique el nombre, la descripcion o las etapas ingresadas.",
                    'icon':"error"
                });
                return false;
            }
        });

        function cargarObjetivo(idObjetivo){
            efectoCargando();
             $.when(
                $.ajax({
                type: "get",
                url: "{{url('/')}}/objetivos/cargar-objetivo-" + idObjetivo,
                data: [],
                contentType: "application/x-www-form-urlencoded",
                success: function (response) {
                    $('#formularioModificarObjetivo').attr('action', '{{url("/")}}/objetivos/modificar-objetivo-' + idObjetivo);
                    $('#nombreObjetivoModificar').val(response.nombre);
                    $('#descripcionObjetivoModificar').val(response.descripcion);
                    $('#etapasObjetivoModificar').val(response.arrayPasos);
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
                Swal.close();
            });
        }

        function clickBorrar(estatus, id) {
            var formulario = $(`#formularioBorrar_${id}`);
            var texto;
            switch (estatus) {
                case 'DESACTIVADO':
                    texto = "¿Seguro que deseas borrar el objetivo?. Si tiene oportunidades relacioandas se desactivaran";
                    break;
            }
            Swal.fire({
                title: texto,
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: "Cancelar",
                denyButtonText: `Borrar`
            }).then((result) => {
                if (result.isConfirmed) {

                } else if (result.isDenied) {
                    efectoCargando();
                    formulario.submit();
                }
            });
        }
    </script>
@endsection
