@extends('Pages.plantilla')

@section('tittle')
    Encuestas
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
    @can('encuestas.agregar')
        <!-- Modal Agregar Encuesta -->
        <div class="modal fade" id="modalAgregarEscuesta" tabindex="-1" aria-labelledby="modalAgregarEscuesta" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                {{-- FORMULARIO DE AGREGAR  --}}
                <form id="formularioCrearEncuesta" action="{{route('encuestas.agregar')}}" method="post">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel"> Agregar Encuesta</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @csrf
                            <div class="row mb-3">
                                <div class="col">
                                    <h4>Descripción</h4>
                                    <input type="text" name="nombreEncuesta" class="form-control" value="{{old('nombreEncuesta')}}" minlength="3" maxlength="255">
                                    @error('nombreEncuesta')
                                        <div class="mensajesErrores p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <h4>Fecha de Inicio</h4>
                                    <input type="date" name="fechaInicio" class="form-control">
                                    @error('fechaInicio')
                                        <div class="mensajesErrores p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <h4>Fecha de Finalización</h4>
                                    <input type="date" name="fechaFin" class="form-control">
                                    @error('fechaFin')
                                        <div class="mensajesErrores p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <h4>Gestor de preguntas</h4>
                                <div id="fb-editor"></div>
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
    @endcan
    @can('encuestas.editar')
        <!-- Modal Modificar Encuesta -->
        <div class="modal fade" id="ModificarModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                {{-- FORMULARIO DE MODIFICAR USUARIO --}}
                <form id="formularioModificarEncuesta"
                action="#"
                method="post">
                <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Modificar Encuestas</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @csrf
                            <div class="row mb-3">
                                <div class="col">
                                    <h4>Descripción</h4>
                                    <input type="text" id="descripcion" name="descripcion" class="form-control" value="{{old('descripcion')}}" minlength="3" maxlength="255">
                                    @error('descripcion')
                                        <div class="mensajesErrores p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <h4>Fecha de Inicio</h4>
                                    <input type="date" id="fechaInicio" name="fechaInicio" class="form-control">
                                    @error('fechaInicio')
                                        <div class="mensajesErrores p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <h4>Fecha de Finalización</h4>
                                    <input type="date" id="fechaFinalizacion" name="fechaFin" class="form-control">
                                    @error('fechaFinalizacion')
                                        <div class="mensajesErrores p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                    @enderror
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <h4>Gestor de preguntas</h4>
                                <div id="fb-editor2"></div>
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
    @endcan
    <div class="container-fluid px-4">
        <h1 class="mt-4">Encuestas</h1>
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-end">
                    @can('encuestas.agregar')
                        <button class="btnCrearUsuario btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarEscuesta" ><i class="fas fa-file me-1"></i> Agregar Encuesta</button>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <table id="tablaUsuarios2" class="table table-striped table-bordered " style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Descripción</th>
                            <th>Periodo</th>
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
    @error('errorBorrar')
        <div class="alert alert-warning" role="alert">
            <div class="mensajesErrores p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
        </div>
    @enderror
@endsection

@section('scripts')
    <script src="/js/generador-contrasenias.js" text="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://formbuilder.online/assets/js/form-builder.min.js"></script>
    <script text="text/javascript">
        var fbEditor, formBuilder, fbEditor2, formBuilder2;
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
                }
            };
            fbEditor = document.getElementById('fb-editor');
            fbEditor2 = document.getElementById('fb-editor2');
            formBuilder = $(fbEditor).formBuilder(options);
            formBuilder2 = $(fbEditor2).formBuilder(options);
            @if (session()->has('encuestaCrearErrores'))
                const modalCrear = new bootstrap.Modal(document.getElementById('modalAgregarEscuesta'));
                modalCrear.show();
            @endif
            @if (session()->has('encuestaModificarErrores'))
                const modalModificar = new bootstrap.Modal(document.getElementById('ModificarModal'));
                modalModificar.show();
            @endif
            var table = $('#tablaUsuarios2').DataTable({
                order: [[0, 'desc']],
                scrollX: true,
                lengthChange: true,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json',
                },
                "processing": true,
                "serverSide": true,
                ajax: {
                    url: "{{route('encuestas.cargar')}}",
                },
                columns: [
                    { data: 'id' },
                    { data: 'nombre'},
                    { data: null,
                        render: function(data, type, row){
                            return (data.fecha_inicio != null) ? `${data.fecha_inicio} - ${data.fecha_fin}` : 'SIN PERIODO';
                        }},
                    { data: 'estatus' },
                    { data: null,
                        render: function(data, type, row){
                            var botones = '';
                            var creando = @can('encuestas.modificar')
                                    '<button id="btnVistaPrevia_'+data.id+'"  class ="btn btn-primary">'+
                                        '<i class="fas fa-file me-1">'+
                                    '</i>&nbsp;VistaPrevia'+
                                    '</button>'+
                                    '<button id="btnModificarEncuesta_'+data.id+'" onclick="cargarEncuesta('+data.id+')" class ="btn btn-warning" data-bs-toggle="modal" data-bs-target="#ModificarModal" >'+
                                        '<i class="fas fa-edit me-1">'+
                                    '</i>&nbsp;Editar'+
                                    '</button>'+
                                    '<button id="btnConfigurarUsuario_'+data.id+'" class ="btn btn-primary">'+
                                        '<i class="fas fa-gear me-1">'+
                                    '</i>&nbsp;Configurar'+
                                    '</button>'+
                                    '<form action="{{url('/')}}/encuestas/iniciar-periodo-' + data.id + '" method="post">'+
                                        '<input type="hidden" value="{{csrf_token()}}" name="_token">'+
                                        '<button class ="btn btn-success">'+
                                            '<i class="fas fa-play">'+
                                        '</i>&nbsp;Iniciar Encuesta'+
                                        '</button>'+
                                    '</form>'+
                                    @endcan
                                    '';
                            var enCurso =
                                @can('encuestas.modificar')
                                    '<form action="{{url('/')}}/encuestas/finalizar-periodo-' + data.id + '" method="post">'+
                                        '<input type="hidden" value="{{csrf_token()}}" name="_token">'+
                                        '<button class ="btn btn-secondary">'+
                                            '<i class="fas fa-stop">'+
                                        '</i>&nbsp;Cerrar Encuesta'+
                                        '</button>'+
                                    '</form>'+
                                @endcan
                                '<button id="btnEnviarCorreoUsuario_'+data.id+'" class ="btn btn-dark">'+
                                    '<i class="fas fa-envelope">'+
                                '</i>&nbsp;Compartir'+
                                '</button>';

                            botones = '';

                            if(data.estatus == 'CREANDO'){
                                botones += creando;
                            }
                            else if(data.estatus == 'ENCURSO'){
                                botones += enCurso;
                            }
                            botones +=
                            '<form id="formularioBorrar_'+data.id+'" action="{{url('/')}}/encuestas/duplicar-' + data.id + '" method="post">'+
                                '<input type="hidden" value="{{csrf_token()}}" name="_token">'+
                                '<button id="btnCerrarEncuestaUsuario_'+data.id+'" class ="btn btn-ligth">'+
                                    '<i class="fas fa-clone">'+
                                '</i>&nbsp;Clonar'+
                                '</button>'+
                            '</form>'+
                            '<form id="formularioBorrar_'+data.id+'" action="{{url('/')}}/encuestas/borrar-' + data.id + '" method="post">'+
                                '<input type="hidden" value="{{csrf_token()}}" name="_token">'+
                                `<button id="borrarUsuario_${data.id}" type="button" onclick="clickBorrar('${data.estatus}', '${data.id}')" class="btn btn btn-danger">`+
                                    '<i class="fas fa-close me-1">'+
                                '</i>Borrar</button>'+
                            '</form>';
                            return botones;
                        }},
                    // Agrega más columnas según tus datos
                ]
            });

        });

        $('#formularioCrearEncuesta').submit(function (e) {
            Swal.fire({
                title: 'Cargando...',
                allowOutsideClick: false,
                showConfirmButton: false,
                html: '<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>'
            });
            var preguntasString = JSON.stringify(formBuilder.actions.getData());
            $('#formularioCrearEncuesta').append($('<input>').attr('type', 'hidden').attr('name', 'preguntasJSON').val(preguntasString));
        });
        $('#formularioModificarEncuesta').submit(function (e) {
            Swal.fire({
                title: 'Cargando...',
                allowOutsideClick: false,
                showConfirmButton: false,
                html: '<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>'
            });
            var preguntasString = JSON.stringify(formBuilder2.actions.getData());
            $('[name="ModificarPreguntasJSON"]').remove();
            $('#formularioModificarEncuesta').append($('<input>').attr('type', 'hidden').attr('name', 'ModificarPreguntasJSON').val(preguntasString));
        });
        function cargarEncuesta(idEncuesta){
            Swal.fire({
                title: 'Cargando...',
                allowOutsideClick: false,
                showConfirmButton: false,
                html: '<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>'
            });
            $('.mensajesErrores').remove();
            var ruta = "{{url('/')}}/encuestas/ver-" + idEncuesta;
                $.when(
                $.ajax({
                type: "get",
                url: ruta,
                data: [],
                contentType: "application/x-www-form-urlencoded",
                success: function (response) {
                    var ruta = "{{url('/')}}/encuestas/modificar-"+response.id;
                    $('#formularioModificarEncuesta')[0].reset();
                    $('#formularioModificarEncuesta').attr('action', ruta);
                    $('#formularioModificarEncuesta').css('display', 'block');
                    $('#descripcion').val(response.nombre);
                    $('#fechaInicio').val(response.fecha_inicio);
                    $('#fechaFinalizacion').val(response.fecha_fin);
                    formBuilder2.actions.setData(response.jsonPregunta);
                    // $('#modificarNombre').val(response[0].nombre);
                    // $('#modificarApellidoPaterno').val(response[0].apellido_paterno);
                    // $('#modificarApellidoMaterno').val(response[0].apellido_materno);
                    // $('#modificarCorreo').val(response[0].email);
                    // $('#modificarTelefono').val(response[0].telefono);
                    // if(response[1] == 'SUPER ADMINISTRADOR'){
                    //     $('#modificarRolUsuario').prop('disabled', true);
                    // }
                    // else{
                    //     $('#modificarRolUsuario').prop('disabled', false);
                    // }
                    // $('#modificarRolUsuario').val(response[1]);
                    // $('#modificarNivelAcceso').val(response[0].nivel_acceso);
                    // $('.nivelAcceso').trigger('change');
                    // let nivelesSeparados = (response[0].niveles != null) ? response[0].niveles.split(',') : [];
                    // console.log(nivelesSeparados);
                    // $('.nivelesSeleccionados').val(nivelesSeparados);

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
            console.log(formulario);
            var texto;
            switch (estatus) {
                case 'CREANDO':
                    texto = "¿Seguro que deseas borrar la encuesta?";
                    break;
                case 'ENCURSO':
                    texto = "¿Seguro que deseas borrar la encuesta?. Se perderán los resultados obtenidos.";
                    break;
                case 'FINALIZADO':
                    texto = "¿Seguro que deseas borrar la encuesta?. Se perderán los resultados obtenidos.";
                    break;

                default:
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

                    formulario.submit();
                }
            });
        }

    </script>
@endsection
