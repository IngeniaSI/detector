@extends('Pages.plantilla')

@section('tittle')
    Respuestas de Encuestas
@endsection


@section('cuerpo')
<style>

    :root {
        --purple: #0d6efd;
        --off-white: #f8f8f8;
        --off-black: #444444;
        --shadow: 0 0 30px #cccccc;
        --xs: 0.2rem;
        --sm: 0.5rem;
        --md: 0.8rem;
        --lg: 1rem;
        --xlg: 1.5rem;
        --xxlg: 2rem;
        --transition: 0.3s linear all;
    }
    .tag {
        background-color: var(--purple);
        border-radius: 10px;
        color: var(--off-white);
        font-size: var(--md);
        margin-bottom: var(--md);
        margin-right: var(--md);
        padding: var(--sm) var(--md);
    }

    .remove-tag {
        cursor: pointer;
        margin-left: 5px;
    }
    .select2-container--open {
        z-index: 9999999
    }
</style>
    <!-- Modal Configurar -->
    <div class="modal fade" id="modalConfigurar" tabindex="-1" aria-labelledby="modalConfigurar" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            {{-- FORMULARIO DE CONFIGURAR  --}}
            <form id="formularioConfigurar" action="#" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">VINCULAR CON PERSONA</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="row mb-3">

                            <div class="row">
                                <div class="col">
                                    <h4>Seleccionar a la persona:</h4>
                                    <select id="personaVinculada" name="personaVinculada" class="form-select selectToo" style="width:100%">

                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="botonVinculante" class="btn btn-primary">Guardar Cambios</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
     <!-- Modal VISTA PREVIA -->
     <div class="modal fade" id="PreviaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            {{-- FORMULARIO Vista Previa USUARIO --}}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Vista Previa de la Encuestas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <h3 id="encabezadoPrevio"></h3>
                        <h4>- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - </h4>
                        <div id="fb-editorPrevio" style="pointer-events:none;"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
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
                        <h4>Seleccione una encuesta:</h4>
                        <select id="excelAExportar" class="form-select selectToo encuestasExistentes" style="width:100%">
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <a id="ligaExportar" href="#" target="_blank">
                        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Exportar</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Respuestas de las Encuestas</h1>
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-end">
                    @can('respuestasEncuesta.exportar')
                        <button class="btnExportarExcel btn btn-success" data-bs-toggle="modal" data-bs-target="#modalExportar"><i class="fas fa-file-excel me-1"></i> Exportar Excel</button>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h4>Filtrar resultados por:</h4>
                        <select id="selectNombre" name="selectNombre" class="form-select selectToo encuestasExistentes " style="width:100%">
                            <option value="TODOS">TODOS</option>
                        </select>
                    </div>
                </div>
                <table id="tablaUsuarios2" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre de la Encuesta</th>
                            <th>Origen</th>
                            <th>Tipo</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    @error('errorBorrar')
        <div class="alert alert-warning" role="alert">
            <div class="mensajesErrores p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
        </div>
    @enderror
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
        var encuestaVincular;
        $(document).ready(function () {
            var options = {
                showActionButtons: false,
                disableFields: [
                'autocomplete',
                'file',
                'button',
                'hidden',
                'select'
                ],
                i18n: {
                    override: {
                    'en-US': {
                        addOption: 'Añadir Opciones +',
                    allFieldsRemoved: 'Todos los campos fueron eliminados',
                    allowMultipleFiles: 'Permitir seleccionar',
                    autocomplete: 'Autocompletar',
                    button: 'Botón',
                    cannotBeEmpty: 'Este campo no puede estar vacio',
                    checkboxGroup: 'Varias respuestas',
                    className: 'Class',
                    clearAllMessage: '¿Estas seguro de querer quitar todos los campos?',
                    clear: 'Eliminar',
                    close: 'Cerrar',
                    content: 'Contenido',
                    copy: 'Copiar al Portapapeles',
                    copyButton: '&#43;',
                    copyButtonTooltip: 'Copiar',
                    dateField: 'Campo de Fecha',
                    description: 'Texto de ayuda',
                    descriptionField: 'Descripción',
                    devMode: 'Modo Desarrollador',
                    editNames: 'Editar Nombres',
                    editorTitle: 'Elementos del form',
                    editXML: 'Editar XML',
                    enableOther: 'Permitir &quot;Otros&quot;',
                    enableOtherMsg: 'Permitir una opción no incluida en la lista',
                    fieldNonEditable: 'Este campo no se puede editar.',
                    fieldRemoveWarning: '¿Estás seguro de que quieres eliminar este campo?',
                    fileUpload: 'Subir Archivo',
                    formUpdated: 'Enciuesta Actulizada',
                    getStarted: 'Arrastre campos de la derecha para crear el formulario',
                    header: 'Titulo',
                    hide: 'editar',
                    hidden: 'Ocultar Campo',
                    inline: 'Inline',
                    inlineDesc: 'Pantalla {type} inline',
                    label: 'Etiqueta',
                    labelEmpty: 'La etiqueta del campo no puede estar vacía',
                    limitRole: 'Limite el acceso a uno o más de los siguientes roles:',
                    mandatory: 'Mandatory',
                    maxlength: 'Tamaño Maximo',
                    minOptionMessage: 'se requiere minimo 2 campos',
                    multipleFiles: 'Multiples Archivos',
                    name: 'Nombre',
                    no: 'No',
                    noFieldsToClear: 'No hay campos para borrar',
                    number: 'Campo Numerico',
                    off: 'Apagado',
                    on: 'Encendido',
                    option: 'Opcional',
                    options: 'Opcionales',
                    optional: 'optional',
                    optionLabelPlaceholder: 'Etiqueta',
                    optionValuePlaceholder: 'Valor',
                    optionEmpty: 'Se regquiere el valor de la option',
                    other: 'Otro',
                    paragraph: 'Párrafo',
                    placeholder: 'Ejemplo ',
                    'placeholder.value': 'Valor',
                    'placeholder.label': 'Etiqueta',
                    'placeholder.text': '',
                    'placeholder.textarea': '',
                    'placeholder.email': 'Ingresa tu correo',
                    'placeholder.placeholder': '',
                    'placeholder.className': 'space separated classes',
                    'placeholder.password': 'Ingresa contraseña',
                    preview: 'Vista Previa',
                    radioGroup: 'Una sola respuesta',
                    radio: 'Radio',
                    removeMessage: 'Remover Elemento',
                    removeOption: 'Remover Opción',
                    remove: '&#215;',
                    required: 'Rquerido',
                    richText: 'Rich Text Editor',
                    roles: 'Acceso',
                    rows: 'Renglones',
                    save: 'Guardar',
                    selectOptions: 'Options',
                    select: 'Seleciona una respuesta',
                    selectColor: 'Select Color',
                    selectionsMessage: 'Permitir selecciones múltiples',
                    size: 'Size',
                    'size.xs': 'Extra Pequeño',
                    'size.sm': 'Pequeño',
                    'size.m': 'Default',
                    'size.lg': 'Grande',
                    style: 'Style',
                    styles: {
                        btn: {
                        'default': 'Default',
                        danger: 'Danger',
                        info: 'Info',
                        primary: 'Primary',
                        success: 'Success',
                        warning: 'Warning'
                        }
                    },
                    subtype: 'Tipo',
                    text: 'Campo de texto corto',
                    textArea: 'Campo de texto largo',
                    toggle: 'Toggle',
                    warning: 'Warning!',
                    value: 'Value',
                    viewJSON: '{  }',
                    viewXML: '&lt;/&gt;',
                    yes: 'Yes'
                    }
                    }
                },
                showActionButtons: false
            };
            fbEditor = document.getElementById('fb-editor');
            fbEditor2 = document.getElementById('fb-editor2');
            fbEditorPrevio = document.getElementById('fb-editorPrevio');

            formBuilder = $(fbEditor).formBuilder(options);
            formBuilder2 = $(fbEditor2).formBuilder(options);
            formBuilderPrevio = $(fbEditorPrevio).formBuilder(options);
            table = $('#tablaUsuarios2').DataTable({
                layout: {
                    topStart: {
                        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                    }
                },
                order: [[0, 'desc']],
                scrollX: true,
                lengthChange: true,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json',
                },
                "processing": true,
                "serverSide": true,
                ajax: {
                    url: "{{route('respuestas.paginacion')}}",
                    data: function(d) {
                        d.selectNombre = $('#selectNombre').val();
                    }
                },
                columns: [
                    { data: 'id' },
                    { data: 'nombre'},
                    { data: 'origen'},
                    { data: null,
                        render: function(data, type, row){
                            let tipo = '';
                            if(data.persona_id != null){
                                tipo = 'PERSONA REGISTRADA';
                            }
                            else{
                                tipo = 'ANONIMO';
                            }
                            return tipo;
                        }},
                    { data: null,
                        render: function(data, type, row){

                            var botones =
                                    '<button id="btnVistaPrevia_'+data.id+'" onclick="cargarEncuesta('+data.id+')" class ="btn btn-primary" data-bs-toggle="modal" data-bs-target="#PreviaModal" >'+
                                        '<i class="fas fa-file me-1">'+
                                    '</i>&nbsp;VER RESULTADO'+
                                    '</button>'+
                                    '<button id="btnConfigurarUsuario_'+data.id+'" onclick="cargarConfiguracion('+data.id+')" class ="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalConfigurar">'+
                                        '<i class="fas fa-gear me-1">'+
                                    '</i>&nbsp;VINCULAR CON PERSONA'+
                                    '</button>'+
                                    '</form>'+
                                    '';

                            return botones;
                        }},
                    // Agrega más columnas según tus datos
                ]
            });

            $.when(
                $.ajax({
                        type: "get",
                        url: "{{route('respuestas.inicializar')}}",
                        data: [],
                        contentType: "application/x-www-form-urlencoded",
                    success: function (response) {
                        $.each(response.encuestas, function (indexInArray, valueOfElement) {
                            $('.encuestasExistentes').append($('<option>').html(valueOfElement.nombre).val(valueOfElement.id));
                        });
                        $('.encuestasExistentes').trigger('change');
                        $.each(response.personas, function (indexInArray, valueOfElement) {
                            $('#personaVinculada').append($('<option>')
                                .html(`${valueOfElement.nombres} ${valueOfElement.apellido_paterno}, ${valueOfElement.telefono_celular}`)
                                .val(valueOfElement.id));
                        });
                        $('#personaVinculada').trigger('change');

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


        function efectoCargando(){
            Swal.fire({
                title: 'Cargando...',
                allowOutsideClick: false,
                showConfirmButton: false,
                html: '<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>'
            });
        }

        function cargarEncuesta(idEncuesta){
            efectoCargando();
            $('.mensajesErrores').remove();
            var ruta = "{{url('/')}}/encuestas/resultados/cargar-resultado-" + idEncuesta;
                $.when(
                $.ajax({
                type: "get",
                url: ruta,
                data: [],
                contentType: "application/x-www-form-urlencoded",
                success: function (response) {
                    $('#encabezadoPrevio').text(response.nombre);
                    $(fbEditorPrevio).formRender({
                        dataType: 'json',
                        formData: response.preguntas
                    });
                    console.log(response);
                    let preguntas = JSON.parse(response.preguntas);
                    let respuestas = JSON.parse(response.resultados);
                    console.log(preguntas, respuestas);
                    //CARGAR RESPUESTAS
                    let arregloRespuesta = Object.keys(respuestas).map((key) => [key, respuestas[key]]);
                $.each(preguntas, function (indexInArray, valueOfElement) {
                    let valorObtenido;
                    arregloRespuesta.forEach(element => {
                        if(element[0] == valueOfElement.name){
                            valorObtenido = element[1];
                        }
                    });
                    switch (valueOfElement.type) {
                        case 'checkbox-group':
                            $(`[name="${valueOfElement.name}[]"]`).val(valorObtenido);
                            break;
                        case 'radio-group':
                            $(`[name="${valueOfElement.name}"][value="${valorObtenido}"]`).prop('checked', true);
                            break;
                        case 'date':
                            $(`[name="${valueOfElement.name}"]`).val(valorObtenido);
                            break;
                        case 'number':
                            $(`[name="${valueOfElement.name}"]`).val(valorObtenido);
                            break;
                        case 'textarea':
                            $(`[name="${valueOfElement.name}"]`).val(valorObtenido);
                            break;
                        case 'text':
                            $(`[name="${valueOfElement.name}"]`).val(valorObtenido);
                            break;
                        default:
                            break;
                    }
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
                Swal.close();
            });
        }

        function cargarConfiguracion(idEncuesta){
            encuestaVincular = idEncuesta;
            $('#formularioConfigurar')[0].reset();
            // var ruta = "{{url('/')}}/encuestas/resultados/vincular-" + idEncuesta + "-" + ;
            // $('#formularioConfigurar')[0].reset();
            // $('#formularioConfigurar').attr('action', ruta);

            // efectoCargando();
            // $('.mensajesErrores').remove();
            //     $.when(
            //     $.ajax({
            //     type: "get",
            //     url: ruta,
            //     data: [],
            //     contentType: "application/x-www-form-urlencoded",
            //     success: function (response) {
            //         var ruta = "{{url('/')}}/encuestas/configurar-"+response.id;


            //         $('#tipoGrafica').val(response.tipoGrafica);
            //         $('#tipoGrafica').trigger('change');
            //         let seccionesSeparadas = (response.seccionesObjetivo != null) ? response.seccionesObjetivo.split(',') : [];
            //         console.log(seccionesSeparadas);
            //         $('#seccionesObjetivo').val(seccionesSeparadas);
            //         $('#seccionesObjetivo').trigger('change');
            //     },
            //     error: function( data, textStatus, jqXHR){
            //         if (jqXHR.status === 0) {
            //             console.log('Not connect: Verify Network.');
            //         } else if (jqXHR.status == 404) {
            //             console.log('Requested page not found [404]');
            //         } else if (jqXHR.status == 500) {
            //             console.log('Internal Server Error [500].');
            //         } else if (textStatus === 'parsererror') {
            //             console.log('Requested JSON parse failed.');
            //         } else if (textStatus === 'timeout') {
            //             console.log('Time out error.');
            //         } else if (textStatus === 'abort') {
            //             console.log('Ajax request aborted.');
            //         } else {
            //             console.log('Uncaught Error: ' + jqXHR.responseText);
            //         }
            //     }
            // })
            // ).then(
            // function( data, textStatus, jqXHR ) {
            //     Swal.close();
            // });
        }

        $('#selectNombre').change(function (e) {
            table.ajax.reload();

        });
        $('#excelAExportar').change(function (e) {
            let idEncuesta = $(this).val();
            $('#ligaExportar').attr('href', `{{url('/')}}/encuestas/resultados/exportar-${idEncuesta}`);
        });

        $('#botonVinculante').click(function (e) {
            efectoCargando();
            let personaSeleccionada = $('#personaVinculada').val();
            var ruta = "{{url('/')}}/encuestas/resultados/vincular-" + encuestaVincular + "-" + personaSeleccionada;
            $('#formularioConfigurar').attr('action', ruta);
            $('#formularioConfigurar').trigger('submit');
        });
    </script>
@endsection
