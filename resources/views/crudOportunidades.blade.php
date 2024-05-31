@extends('Pages.plantilla')

@section('tittle')
    Oportunidades
@endsection


@section('cuerpo')
<style>
    .select2-container--open {
        z-index: 9999999
    }
</style>
<link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/tutorials/timelines/timeline-1/assets/css/timeline-1.css">
    <!-- Modal Historico-->
<div class="modal fade" id="modalHistorico" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Historico de <span id="nombrePersonaHistorico"></span>, <span id="nombreMeta"></span></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <section class="bsb-timeline-1 py-3 py-xl-5">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-10 col-md-8 col-xl-6">

                                <ul id="lineaTiempoActividades" class="timeline">
                                    
                                </ul>

                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Agregar oportuinidad -->
<div class="modal fade" id="modalNuevaOportunidad" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        {{-- FORMULARIO DE AGREGAR oportuinidad --}}
        <form id="fomrularioAgregarOportunidad" action="{{route('oportunidades.agregar')}}" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> Crear nueva oportunidad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <h4>Seleccione una objetivo:</h4>
                            <select id="oportunidadVinculada" name="oportunidadVinculada" class="form-select selectToo" style="width:100%">
                                <option value="0">SIN DATO</option>
                            </select>
                            <h4>Seleccione una persona:</h4>
                            <select id="personaVinculada" name="personaVinculada" class="form-select selectToo" style="width:100%">
                                <option value="0">SIN DATO</option>
                            </select>
                            <h4>¿Quién promovera esta persona?:</h4>
                            <select id="promotorVinculado" name="promotorVinculado" class="form-select selectToo" style="width:100%">
                                <option value="0">SIN DATO</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Crear</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Modal Agregar actividad -->
<div class="modal fade" id="modalNuevaActividad" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        {{-- FORMULARIO DE AGREGAR actividad --}}
        <form id="formularioAgregarActividad" action="#" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> Agregar nueva actividad</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <h4>Fecha de la actividad:</h4>
                            <input type="date" id="fechaRegistro" name="fechaRegistro" value="{{date('Y-m-d')}}" class="form-control">
                            <h4>Hora de la actividad:</h4>
                            <input type="time" id="horaActividad" name="horaActividad" value="{{date('H:i:s')}}" class="form-control">
                            <h4>Actividad realizada:</h4>
                            <input type="text" id="actividadRealizada" name="actividadRealizada" class="form-control">
                            <h4>Observaciones/Respuesta por parte de la Persona:</h4>
                            <textarea name="respuestaPersona" id="respuestaPersona" cols="30" rows="10" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="agregarActividadSubmit" class="btn btn-primary">Agregar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Modal EXPORTAR -->
<div class="modal fade" id="modalExportar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        {{-- FORMULARIO Vista Previa USUARIO --}}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Exportando a excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <h4>Seleccione un promotor:</h4>
                    <select id="selectPromotores" name="selectPromotores" class="form-select selectToo" style="width:100%">
                        <option value="0">SIN DATO</option>
                    </select>
                    <h4>Filtrar por estatus:</h4>
                    <select id="estatusSeleccionados" class="form-select selectToo" style="width:100%;" name="estatusSeleccionados[]" multiple="multiple">
                        <option value="PENDIENTE">PENDIENTE</option>
                        <option value="INICIADO">INICIADO</option>
                        <option value="COMPROMISO">COMPROMISO</option>
                        <option value="CUMPLIDO">CUMPLIDO</option>
                        <option value="PERDIDO">PERDIDO</option>
                    </select>
                </div>
                <br>
                <small>*Sin estatus filtrados exporta todos los registros de la persona </small>
            </div>
            <div class="modal-footer">
                <a id="ligaExportar" href="#">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Exportar</button>
                </a>
            </div>
        </div>
    </div>
</div>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Oportunidades</h1>
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-end">
                        <button class="btn btn btn-success" data-bs-toggle="modal" data-bs-target="#modalNuevaOportunidad"><i class="fas fa-plus me-1"></i> Crear oportunidad</button>
                        @can('crudOportunidades.exportar')
                            <button class="btn btn btn-success" data-bs-toggle="modal" data-bs-target="#modalExportar"><i class="fas fa-file-excel me-1"></i>Exportar Excel</button>
                        @endcan
                </div>
            </div>
            <div class="card-body">

                <table id="tabla" class="table table-striped table-bordered " style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre Oportunidad</th>
                            <th>Nombre Persona</th>
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
                    url: "{{route('oportunidades.cargarOportunidades')}}",
                    data: function(d) {
                        d.fechaInicio = $('#fechaInicioFiltro').val();
                        d.fechaFin = $('#fechaFinFiltro').val();
                    }
                },
                columns: [
                    { data: 'id' },
                    { data: 'nombre'},
                    { data: 'nombre_completo'},
                    { data: null ,
                        render: function(data, type, row){
                            //HACER UNA FUNCION PARA PODER IR ELIMINANDO OPCIONES ANTERIORES A MEDIDA QUE VA AVANZANDO
                            var select =
                            '<select id="estatusSelect'+data.id+'" onchange="cambiarEstatus('+data.id+')" name="select" class="form-control">'+
                                '<option class="' + ((data.estatus == "INICIADO" || data.estatus == "COMPROMISO" || data.estatus == "CUMPLIDO" || data.estatus == "PERDIDO") ? 'd-none' : '') + '" ' + ((data.estatus == "PENDIENTE") ? "selected" : null) + ' value="PENDIENTE">PENDIENTE</option>'+
                                '<option class="' + ((data.estatus == "COMPROMISO" || data.estatus == "CUMPLIDO" || data.estatus == "PERDIDO") ? 'd-none' : '') + '" ' + ((data.estatus == "INICIADO") ? "selected" : null) + ' value="INICIADO">INICIADO</option>'+
                                '<option class="' + ((data.estatus == "CUMPLIDO" || data.estatus == "PERDIDO") ? 'd-none' : '') + '" ' + ((data.estatus == "COMPROMISO") ? "selected" : null) + ' value="COMPROMISO">COMPROMISO</option>'+
                                '<option class="' + ((data.estatus == "PERDIDO") ? 'd-none' : '') + '" ' + ((data.estatus == "CUMPLIDO") ? "selected" : null) + ' value="CUMPLIDO">CUMPLIDO</option>'+
                                '<option class="' + ((data.estatus == "CUMPLIDO") ? 'd-none' : '') + '" ' + ((data.estatus == "PERDIDO") ? "selected" : null) + ' value="PERDIDO">PERDIDO</option></select>';
                            return select;
                        }},
                    { data: null,
                        render: function(data, type, row){
                            var botones =
                                '<button onclick="cargarHistorico('+data.id+')" id="btnHistorico_'+data.id+'" class ="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalHistorico">'+
                                        '<i class="fas fa-clock me-1">'+
                                    '</i>&nbsp;Historico'+
                                    '</button>';

                                    botones += (data.estatus != 'CUMPLIDO' && data.estatus != 'PERDIDO') ? '<button class="btn btn btn-secondary" id="botonAgregarActividad_'+data.id+'" data-bs-toggle="modal" data-bs-target="#modalNuevaActividad" onclick="cargarFormularioAgregarActividad('+data.id+')"><i class="fas fa-tasks me-1"></i>Agregar actividad</button>': '';
                            return botones;
                        }},
                    // Agrega más columnas según tus datos
                ]
            });


            $.when(
                $.ajax({
                type: "get",
                url: "{{route('oportunidades.inicializar')}}",
                data: [],
                contentType: "application/x-www-form-urlencoded",
                success: function (response) {
                    console.log(response);
                    $.each(response.objetivos, function (indexInArray, valueOfElement) {
                        console.log(valueOfElement);
                        $('#oportunidadVinculada').append($('<option>')
                            .html(valueOfElement.nombre)
                            .val(valueOfElement.id));
                    });
                    $('#oportunidadVinculada').trigger('change');
                    $.each(response.personas, function (indexInArray, valueOfElement) {
                        $('#personaVinculada').append($('<option>')
                            .html(`${valueOfElement.nombres} ${valueOfElement.apellido_paterno}, ${valueOfElement.telefono_celular}`)
                            .val(valueOfElement.id));
                    });
                    $('#personaVinculada').trigger('change');
                    $.each(response.promotores, function (indexInArray, valueOfElement) {
                        $('#promotorVinculado').append($('<option>')
                            .html(`${valueOfElement.nombres} ${valueOfElement.apellido_paterno}, ${valueOfElement.telefono_celular}`)
                            .val(valueOfElement.id));
                    });
                    $.each(response.promotores, function (indexInArray, valueOfElement) {
                        $('#selectPromotores').append($('<option>')
                            .html(`${valueOfElement.nombres} ${valueOfElement.apellido_paterno}, ${valueOfElement.telefono_celular}`)
                            .val(valueOfElement.id));
                    });
                    $('#promotorVinculado').trigger('change');

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

        $('#fomrularioAgregarOportunidad').submit(function (e) {
            let oportunidad = $('#oportunidadVinculada').val();
            let persona = $('#personaVinculada').val();
            let promotor = $('#promotorVinculado').val();
            if(oportunidad == 0 || persona == 0 || promotor == 0){
                Swal.fire({
                    'title':"Error",
                    'text':"No se pudo crear la oportunidad. Verifique el objetivo, la persona vinculada o el promotor.",
                    'icon':"error"
                });
                return false;
            }
        });

        function cambiarEstatus(oportunidadId){
            var entro = false;
            efectoCargando();
            let valorEstatusSelect = $('#estatusSelect' + oportunidadId).val();
            $.when(
                $.ajax({
                type: "post",
                url: "{{route('oportunidades.cambiarEstatus')}}",
                data: [
                    {
                    name:"_token",
                    value : "{{csrf_token()}}",
                    },
                    {
                    name:"idOportunidad",
                    value : oportunidadId,
                    },
                    {
                    name:"estatusNuevo",
                    value : valorEstatusSelect
                    }
                ],
                contentType: "application/x-www-form-urlencoded",
                success: function (response) {
                    entro = true;
                    Swal.close();
                    if(response){
                        Swal.fire({
                            'title':"Éxito",
                            'text':"Se ha cambiado el estatus de la oportunidad",
                            'icon':"success"
                        });
                        var options = $('#estatusSelect' + oportunidadId).find('option');
                        if(valorEstatusSelect == 'CUMPLIDO' || valorEstatusSelect == 'PERDIDO'){

                            $('#botonAgregarActividad_'+oportunidadId).remove();
                        }
                        var esconderOpciones = true;
                        options.each(function(){
                            if (esconderOpciones) {
                                $(this).addClass('d-none');
                            }
                            if ($(this).val() == valorEstatusSelect) {
                                $(this).removeClass('d-none');
                                if(valorEstatusSelect == 'CUMPLIDO'){
                                    esconderOpciones = true;
                                }
                                else {
                                    esconderOpciones = false;
                                }
                            }
                        });
                    }
                    else{
                        Swal.fire({
                            'title':"Error",
                            'text':"Ha ocurrido un error al cambiar el estatus de la oportunidad",
                            'icon':"error"
                        });
                    }
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
                if(!entro){
                    Swal.close();
                }
            });
        }

        function cargarFormularioAgregarActividad(idOportunidad){
            $('#formularioAgregarActividad').attr('action', '{{url('/')}}/crudOportunidades/agregar-actividad-'+idOportunidad);
            $('#respuestaPersona').val('');
            $('#actividadRealizada').val('');
            $('#fechaRegistro').val('{{date("Y-m-d")}}');
            $('#horaActividad').val('{{date("H:i:s")}}');
        }

        $('#agregarActividadSubmit').click(function (e) {
            let actividadRealizada = $('#actividadRealizada').val();
            let fechaRegistro = $('#fechaRegistro').val();
            let horaActividad = $('#horaActividad').val();

            if(fechaRegistro != '' && horaActividad != null && actividadRealizada != ''){
                efectoCargando();
                $('#formularioAgregarActividad').trigger('submit');
            }
            else{
                Swal.fire({
                    title:'Error',
                    text:'Hubo un error al agregar la actividad. Verifique la fecha, hora o actividad realizada',
                    icon:'error'
                });
            }
        });

        function cargarHistorico(idOportunidad){
            var entro = false;
            efectoCargando();
            $.when(
                $.ajax({
                type: "get",
                url: "{{url('/')}}/crudOportunidades/cargar-seguimientos-" + idOportunidad,
                data: [],
                contentType: "application/x-www-form-urlencoded",
                success: function (response) {
                    entro = true;
                    Swal.close();
                    $('#nombrePersonaHistorico').text(response.nombrePersona);
                    $('#nombreMeta').text(response.nombreOportunidad)
                    $('#lineaTiempoActividades').html('');
                    $('#lineaTiempoActividades').addClass('timeline');
                    $.each(response.seguimientos, function (indexInArray, valueOfElement) {
                        $('#lineaTiempoActividades').append(
                            $('<li>').addClass('timeline-item').append(
                                $('<div>').addClass("timeline-body").append(
                                    $('<div>').addClass("timeline-content").append(
                                        $('<div>').addClass("card border-0").append(
                                            $('<div>').addClass("card-body p-0").append(
                                                $('<h5>').addClass("card-subtitle text-secondary mb-1").text(valueOfElement.fecha_registro + ' ' + valueOfElement.hora_registro),
                                                $('<h2>').addClass("card-title mb-3").text(valueOfElement.accion)
                                            )
                                        )
                                    )
                                )
                            )
                        );
                    });
                    if(response.seguimientos.length == 0){
                        $('#lineaTiempoActividades').removeClass('timeline');
                        $('#lineaTiempoActividades').append(
                            $('<div>').addClass("card border-0").append(
                                $('<div>').addClass("card-body p-0").append(
                                    $('<h5>').addClass("card-subtitle text-secondary mb-1").text('Sin actividades registradas'),
                                    $('<h2>').addClass("card-title mb-3").text('Este historico no tiene ninguna actividad registrada')
                                )
                            )
                        );
                    }
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
                if(!entro){
                    Swal.close();
                }
            });
        }


        $('#selectPromotores').change(function (e) {
            promotorParaExportar = $('#selectPromotores').val();
            if(promotorParaExportar > 0){
                $('#ligaExportar').attr('href', `{{route('oportunidades.exportarParaPromotor')}}?idPromotor=${promotorParaExportar}&estatusSeleccionado=${estatusParaExportar}`);
                $('#ligaExportar').attr('target', '_blank');
            }
            else{
                $('#ligaExportar').attr('href', '#');
                $('#ligaExportar').attr('target', null);
            }
        });
        $('#estatusSeleccionados').change(function (e) {
            var auxEstatus = $('#estatusSeleccionados').val();
            estatusParaExportar = "";
            $.each(auxEstatus, function (indexInArray, valueOfElement) {
                estatusParaExportar += valueOfElement + ',';
            });
            estatusParaExportar = estatusParaExportar.slice(0, -1);
            if(promotorParaExportar > 0){
                $('#ligaExportar').attr('href', `{{route('oportunidades.exportarParaPromotor')}}?idPromotor=${promotorParaExportar}&estatusSeleccionado=${estatusParaExportar}`);
                $('#ligaExportar').attr('target', '_blank');
            }
            else{
                $('#ligaExportar').attr('href', '#');
                $('#ligaExportar').attr('target', null);
            }
        });

        $('#ligaExportar').click(function (e) {
            if(promotorParaExportar == 0){
                Swal.fire({
                    'title':'Atención',
                    'text':'Debe seleccionar un promotor',
                    'icon':'warning'
                });
            }
        });
    </script>
@endsection
