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
    @can('crudUsuarios.create')
        <!-- Modal Agregar Encuesta -->
        <div class="modal fade" id="AgregarModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                {{-- FORMULARIO DE AGREGAR  --}}
                <form id="formularioCrearEncuesta" action="{{route('encuestas.agregar')}}" method="post">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel"> Agregar Encuestas</h5>
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
    @can('crudUsuarios.edit')
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
                                    <input type="text" name="descripcion" class="form-control" value="{{old('descripcion')}}" minlength="3" maxlength="255">
                                    @error('descripcion')
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
                                    <input type="date" name="fechaFinalizacion" class="form-control">
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
                    @can('crudUsuarios.create')
                        <button class="btnCrearUsuario btn btn-primary" data-bs-toggle="modal" data-bs-target="#AgregarModal" ><i class="fas fa-file me-1"></i> Agregar Encuesta</button>
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
        jQuery(function($) {
  var options = {
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
    },
    $fbTemplate = $(document.getElementById('fb-editor'));
  $fbTemplate.formBuilder(options);
  $fbTemplate2 = $(document.getElementById('fb-editor2'));
  $fbTemplate2.formBuilder(options);
});

        var niveles = [];
        $(document).ready(function() {
            @if (session()->has('formularioCrearErrores'))
                const modalCrear = new bootstrap.Modal(document.getElementById('AgregarModal'));
                modalCrear.show();
            @endif
            @if (session()->has('formularioModificarErrores'))
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
                            botones = '<button id="btnModificarEncuesta_'+data.id+'" class ="btn btn-warning" data-bs-toggle="modal" data-bs-target="#ModificarModal" >'+
                                    '<i class="fas fa-edit me-1">'+
                                '</i>&nbsp;Editar'+
                                '</button>'+
                                '<button id="btnConfigurarUsuario_'+data.id+'" class ="btn btn-primary" >'+
                                    '<i class="fas fa-gear me-1">'+
                                '</i>&nbsp;Configurar'+
                                '</button>'+
                                '<button id="btnIniciarEncuestaUsuario_'+data.id+'" class ="btn btn-success">'+
                                    '<i class="fas fa-play">'+
                                '</i>&nbsp;Iniciar Encuesta'+
                                '</button>'+
                                '<button id="btnCerrarEncuestaUsuario_'+data.id+'" class ="btn btn-secondary">'+
                                    '<i class="fas fa-stop">'+
                                '</i>&nbsp;Cerrar Encuesta'+
                                '</button>'+
                                '<button id="btnEnviarCorreoUsuario_'+data.id+'" class ="btn btn-dark">'+
                                    '<i class="fas fa-envelope">'+
                                '</i>&nbsp;Enviar por Correo'+
                                '</button>'+
                                '<button id="btnCerrarEncuestaUsuario_'+data.id+'" class ="btn btn-ligth">'+
                                    '<i class="fas fa-clone">'+
                                '</i>&nbsp;Clonar'+
                                '</button>'+
                                '<form action="{{url('/')}}/gestor-usuarios/borrar-usuario-' + data.id + '" method="post">'+
                                '<a id="borrarUsuario_'+data.id+'" type="button" class="btn btn btn-danger">'+
                                '<input type="hidden" value="{{csrf_token()}}" name="_token">'+
                                '<i class="fas fa-close me-1">'+
                                '</i>Borrar</a>'+
                                '</form>'+
                            '';
                            return botones;
                        }},
                    // Agrega más columnas según tus datos
                ]
            });
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



    </script>
@endsection
