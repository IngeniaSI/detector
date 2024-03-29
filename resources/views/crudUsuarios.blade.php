@extends('Pages.plantilla')

@section('tittle')
    Usuarios del Sistema
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
</style>
    <!-- Modal Agregar Usuario -->
    <div class="modal fade" id="AgregarModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            {{-- FORMULARIO DE AGREGAR USUARIO --}}
            <form id="formularioCrearUsuario" action="{{route('crudUsuario.crear')}}" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"> Agregar Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="col">
                                <h4>Nombre</h4>
                                <input type="text" name="nombre" class="form-control" value="{{old('nombre')}}" minlength="3" maxlength="255">
                                @error('nombre')
                                    <div class="mensajesErrores p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                @enderror
                            </div>
                            <div class="col">
                                <h4>Apellido paterno</h4>
                                <input type="text" name="apellido_paterno" class="form-control" value="{{old('apellido_paterno')}}" minlength="3" maxlength="255">
                                @error('apellido_paterno')
                                    <div class="mensajesErrores p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                @enderror
                            </div>
                            <div class="col">
                                <h4>Apellido materno</h4>
                                <input type="text" name="apellido_materno" class="form-control" value="{{old('apellido_materno')}}" minlength="3" maxlength="255">
                                @error('apellido_materno')
                                    <div class="mensajesErrores p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                @enderror
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col">
                                <h4>Correo</h4>
                                <input type="email" name="correo" value="{{old('correo')}}" class="form-control" minlength="3" maxlength="255">
                                @error('correo')
                                    <div class="mensajesErrores p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                @enderror
                            </div>
                            <div class="col">
                                <h4>Telefono</h4>
                                <input type="text" name="telefono" value="{{old('telefono')}}" class="form-control" minlength="3" maxlength="255">
                                @error('telefono')
                                    <div class="mensajesErrores p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                @enderror
                            </div>
                            <div class="col">

                            </div>
                        </div>
                        <br>
                        <div class="row justify-content-between">
                            <div class="col-8">
                                <h4>Contraseña</h4>
                                <input type="password" class="form-control contraseniaRandom" name="contrasenia" value="{{old('contrasenia')}}" minlength="3" maxlength="255">
                                @error('contrasenia')
                                    <div class="mensajesErrores p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                @enderror
                            </div>
                            <div class="col-auto mt-auto">
                                <button class="botonRevelarContrasenia btn btn-info" type="button">Revelar contraseña</button>
                                <button class="botonGenerarClaveRandom btn btn-warning" type="button">Generar contraseña</button>
                            </div>
                        </div>

                        <h4>Roles</h4>
                        <select class="form-control" name="rolUsuario">
                            <option value="-1">Selecciona un rol</option>
                            @foreach ($roles as $rol)
                                <option value="{{$rol->name}}">{{str_replace('_', ' ', $rol->name)}}</option>
                            @endforeach
                        </select>
                        @error('rolUsuario')
                            <div class="mensajesErrores p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                        @enderror
                        <br>
                        <h4>Nivel de acceso</h4>
                        <select class="form-control" name="nivelAcceso">
                            <option value="-1">Seleccionar un nivel de acceso</option>
                            <option value="ENTIDAD">Entidad</option>
                            <option value="DISTRITO FEDERAL">Distrito Federal</option>
                            <option value="DISTRITO LOCAL">Distrito Local</option>
                            <option value="MUNICIPIO">Municipio</option>
                            <option value="SECCION">Seccion</option>
                        </select>
                        @error('nivelAcceso')
                            <div class="mensajesErrores p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                        @enderror
                        <br>
                        <h4>Niveles asignados</h4>
                        <div class="row justify-content-between">
                            <div class="col-10">
                                <input type="text" id="inputEtiquetaCrear" class="form-control" placeholder="',' para agregar etiqueta">
                            </div>
                            <div class="col-auto">
                                <button type="button" id="agregarEtiquetaCrear" class="btn btn-primary">Agregar</button>
                            </div>
                        </div>
                        <div class="mt-3 contenedorEtiquetasCrear">
                            <!-- <span class="tag">oh my God <span class="remove-tag">&#10006;</span></span>
                            <span class="tag">second tag <span class="remove-tag">&#10006;</span></span>
                            <span class="tag">tag3 <span class="remove-tag">&#10006;</span></span> -->
                        </div>
                        @error('errorValidacion')
                            <div class="mensajesErrores p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                        @enderror
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary">Crear</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal Modificar Usuario -->
    <div class="modal fade" id="ModificarModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            {{-- FORMULARIO DE MODIFICAR USUARIO --}}
            <form id="formularioModificarUsuario"
            action="@if (session()->has('formularioModificarErrores')) {{route('crudUsuario.editar', session('usuarioAModificar'))}} @endif "
            method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"> Modificar Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="col">
                                <h4>Nombre</h4>
                                <input type="text" class="form-control" id="modificarNombre" name="nombre" value="{{old('nombre')}}" minlength="3" maxlength="255">
                                @error('nombre')
                                    <div class="mensajesErrores p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                @enderror
                            </div>
                            <div class="col">
                                <h4>Apellido paterno</h4>
                                <input type="text" class="form-control"  id="modificarApellidoPaterno" name="apellido_paterno" value="{{old('apellido_paterno')}}" minlength="3" maxlength="255">
                                @error('apellido_paterno')
                                    <div class="mensajesErrores p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                @enderror
                            </div>
                            <div class="col">
                                <h4>Apellido materno</h4>
                                <input type="text" class="form-control"  id="modificarApellidoMaterno" name="apellido_materno" value="{{old('apellido_materno')}}" minlength="3" maxlength="255">
                                @error('apellido_materno')
                                    <div class="mensajesErrores p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                @enderror
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col">
                                <h4>Correo</h4>
                                <input type="email" class="form-control"  id="modificarCorreo" name="correo" value="{{old('correo')}}" minlength="3" maxlength="255">
                                @error('correo')
                                    <div class="mensajesErrores p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                @enderror
                            </div>
                            <div class="col">
                                <h4>Telefono</h4>
                                <input type="text" id="modificarTelefono" name="telefono" value="{{old('telefono')}}" class="form-control" minlength="3" maxlength="255">
                                @error('telefono')
                                    <div class="mensajesErrores p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                @enderror
                            </div>
                            <div class="col">

                            </div>
                        </div>
                        <br>
                        <div class="row justify-content-between">
                            <div class="col-8">
                                <h4>Contraseña</h4>
                                <input type="password" class="form-control contraseniaRandom" name="contrasenia" value="{{old('contrasenia')}}" minlength="3" maxlength="255">
                                @error('contrasenia')
                                    <div class="mensajesErrores p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                                @enderror
                            </div>
                            <div class="col mt-auto">
                                <button class="botonRevelarContrasenia btn btn-info" type="button">Revelar contraseña</button>
                                <button class="botonGenerarClaveRandom btn btn-warning" type="button">Generar contraseña</button>
                            </div>
                        </div>
                        <br>
                        <h4>Roles</h4>
                        <select id="modificarRolUsuario" class="form-control"  name="rolUsuario" @disabled(old('rolUsuario') == null)>
                            @foreach ($roles as $rol)
                                <option value="{{$rol->name}}">{{str_replace('_', ' ', $rol->name)}}</option>
                            @endforeach
                        </select>
                        @error('rolUsuario')
                            <div class="mensajesErrores p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                        @enderror
                        <br>
                        <h4>Nivel de acceso</h4>
                        <select id="modificarNivelAcceso" class="form-control" name="nivelAcceso">
                            <option value="-1">Seleccionar un nivel de acceso</option>
                            <option value="ENTIDAD">Entidad</option>
                            <option value="DISTRITO FEDERAL">Distrito Federal</option>
                            <option value="DISTRITO LOCAL">Distrito Local</option>
                            <option value="MUNICIPIO">Municipio</option>
                            <option value="SECCION">Seccion</option>
                        </select>
                        @error('nivelAcceso')
                            <div class="mensajesErrores p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                        @enderror
                        <br>
                        <h4>Niveles asignados</h4>
                        <div class="row justify-content-between">
                            <div class="col-10">
                                <input type="text" id="inputEtiquetaModificar" class="form-control" placeholder="',' para agregar etiqueta">
                            </div>
                            <div class="col-auto">
                                <button type="button" id="agregarEtiquetaModificar" class="btn btn-primary">Agregar</button>
                            </div>
                        </div>
                        <div class="mt-3 contenedorEtiquetasModificar">
                            <!-- <span class="tag">oh my God <span class="remove-tag">&#10006;</span></span>
                            <span class="tag">second tag <span class="remove-tag">&#10006;</span></span>
                            <span class="tag">tag3 <span class="remove-tag">&#10006;</span></span> -->
                        </div>
                        @error('errorValidacion')
                            <div class="mensajesErrores p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                        @enderror
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
        <h1 class="mt-4">Usuarios del Sistema</h1>
        <div class="card mb-4">
            <div class="card-header">
                <center>
                    <button class="btnCrearUsuario btn btn-primary" data-bs-toggle="modal" data-bs-target="#AgregarModal" ><i class="fas fa-user me-1"></i> Crear usuario</button>
                </center>
            </div>
            <div class="card-body">
                <table id="tablaUsuarios2" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre Completo</th>
                            <th>Correo</th>
                            <th>Rol</th>
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
<script>
    $(document).ready(function() {
        @if (session()->has('formularioCrearErrores'))
            const modalCrear = new bootstrap.Modal(document.getElementById('AgregarModal'));
            modalCrear.show();
        @endif
        @if (session()->has('formularioModificarErrores'))
            const modalModificar = new bootstrap.Modal(document.getElementById('ModificarModal'));
            modalModificar.show();
        @endif
        var table = $('#tablaUsuarios2').DataTable( {
            lengthChange: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json',
            },
            buttons: [ 'copy', 'excel', 'csv', 'pdf', 'colvis' ]
        });

        table.buttons().container()
        .appendTo( '#example_wrapper .col-md-6:eq(0)' );
    });

    //FUNCION PARA ASIGNAR UNA CLAVE ALEATORIA Y ASIGNARLA EN EL FORMULARIO DE MODIFICAR
    $('.botonGenerarClaveRandom').click(function (e) {
        $('.contraseniaRandom').val(getPassword());
    });

    //FUNCION PARA ASIGNAR UNA CLAVE ALEATORIA Y ASIGNARLA EN EL FORMULARIO DE MODIFICAR
    $('.botonRevelarContrasenia').click(function (e) {
        if($('.contraseniaRandom').attr('type') == 'password'){
            $('.contraseniaRandom').attr('type', 'text')
        }
        else{
            $('.contraseniaRandom').attr('type', 'password')
        }
    });

    // FUNCION PARA CARGAR TABLA DE USUARIOS
    $(document).ready(function () {
        $.when(
            $.ajax({
                type: "get",
                url: "{{route('crudUsuario.todos')}}",
                data: [],
                contentType: "application/x-www-form-urlencoded",
            success: function (response) {
                if(response.length == 0){
                    var nuevaFila = $('<tr>').append(
                        $('<td>').attr('colspan', 99).text('Sin registros')
                    );
                    $('#tablaUsuarios2 tbody').append(nuevaFila);
                }
                $.each(response, function (index, elemento) {
                    var apellidoMaterno = (elemento.apellido_materno != null) ? elemento.apellido_materno : '';
                    $('#tablaUsuarios2').DataTable().row.add([
                        elemento.id, elemento.nombre + ' ' + elemento.apellido_paterno + ' ' + apellidoMaterno, elemento.email, elemento.name,
                        '<button id="btnModificarUsuario_'+elemento.id+'" class ="btn btn-success" data-bs-toggle="modal" data-bs-target="#ModificarModal">'+
                            '<i class="fas fa-edit me-1">'+
                        '</i>Editar'+
                        '</button>'+
                        '<form action="{{url('/')}}/gestor-usuarios/borrar-usuario-' + elemento.id + '" method="post">'+
                        '<a id="borrarUsuario_'+elemento.id+'" type="button" class="btn btn btn-danger">'+
                        '<input type="hidden" value="{{csrf_token()}}" name="_token">'+
                        '<i class="fas fa-close me-1">'+
                        '</i>Borrar</a>'+
                        '</form>'
                    ]).draw();
                    $('#borrarUsuario_' + elemento.id).click(clickBorrar);
                });
                $('[id^="btnModificarUsuario"]').click(cargarFormularioModificar);
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


    // FUNCION ABRIR FORMULARIO CREAR USUARIO
    $('.btnCrearUsuario').click(function (e) {
        $('#formularioCrearUsuario')[0].reset();
        $('#formularioCrearUsuario').css('display', 'block');
        $('.mensajesErrores').remove();
        $('.contenedorEtiquetasCrear').html('');
        tags =[];
    });

    // FUNCION CARGAR FORMULARIO MODIFICAR
    function  cargarFormularioModificar(e) {
        tagsMod = [];
        $('.contenedorEtiquetasModificar').html('');
        $('.mensajesErrores').remove();
        var idUsuario = $(this).attr('id').split('_');
            var ruta = "{{url('/')}}/gestor-usuarios/obtener-" + idUsuario[1];
            $.when(
            $.ajax({
            type: "get",
            url: ruta,
            data: [],
            contentType: "application/x-www-form-urlencoded",
            success: function (response) {
                    var ruta = "{{url('/')}}/gestor-usuarios/editar-usuario-"+response[0].id;
                    $('#formularioModificarUsuario')[0].reset();

                    $('#modificarNombre').val(response[0].nombre);
                $('#modificarApellidoPaterno').val(response[0].apellido_paterno);
                $('#modificarApellidoMaterno').val(response[0].apellido_materno);
                $('#modificarCorreo').val(response[0].email);
                $('#modificarTelefono').val(response[0].telefono);
                if(response[1] == 'SUPER ADMINISTRADOR'){
                    $('#modificarRolUsuario').prop('disabled', true);
                }
                else{
                    $('#modificarRolUsuario').prop('disabled', false);
                }
                $('#modificarRolUsuario').val(response[1]);
                $('#modificarNivelAcceso').val(response[0].nivel_acceso);

                let etiquedasPreprocesar = (response[0].niveles != null) ? response[0].niveles.split(',') : [];
                $.each(etiquedasPreprocesar, function (i, valor) {
                    createTagMod(valor);
                });

                $('#formularioModificarUsuario').attr('action', ruta);
                $('#formularioModificarUsuario').css('display', 'block');
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
    }

    // FUNCION CERRAR FORMULARIO
    $('.cerrarFormulario').click(function (e) {
        $('#formularioCrearUsuario')[0].reset();
        $('#formularioModificarUsuario')[0].reset();
        $('#formularioCrearUsuario').css('display', 'none');
        $('#formularioModificarUsuario').css('display', 'none');
        });


    function clickBorrar() {
        var boton = $(this);
        var formulario = $(this).parent();
        Swal.fire({
        title: "¿Seguro que deseas borrar el usuario?",
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: "Cancelar",
        denyButtonText: `Borrar`
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
        } else if (result.isDenied) {
            formulario.submit();
        }
        });


    }


    const button = document.querySelector('#agregarEtiquetaCrear');
    const tagInput = document.querySelector('#inputEtiquetaCrear');

    const tagContainer = document.querySelector('.contenedorEtiquetasCrear');
    let tags = [];

    const buttonMod = document.querySelector('#agregarEtiquetaModificar');
    const tagInputMod = document.querySelector('#inputEtiquetaModificar');

    const tagContainerMod = document.querySelector('.contenedorEtiquetasModificar');
    let tagsMod = [];

    const createTag = (tagValue) => {
        const value = tagValue.trim();

        if (value === '' || tags.includes(value)) return;

        const tag = document.createElement('span');
        tag.setAttribute('class', 'tag');

        const valor = document.createElement('span');
        valor.setAttribute('class', 'valor');
        valor.innerHTML = value;
        tag.appendChild(valor);

        const close = document.createElement('span');
        close.setAttribute('class', 'remove-tag');
        close.innerHTML = '&#10006;';
        close.onclick = handleRemoveTag;

        tag.appendChild(close);
        tagContainer.appendChild(tag);
        tags.push(tag);

        tagInput.value = '';
        tagInput.focus();
    };

    const handleRemoveTag = (e) => {
        const indexEtiqueta = tags.findIndex(function(elemento, i){
            if(elemento.childNodes[0].innerHTML == e.target.parentElement.childNodes[0].innerHTML){
                return true;
            }
        });
        e.target.parentElement.remove()
        if(indexEtiqueta > -1){
            tags.splice(indexEtiqueta, 1);
        }
    };

    tagInput.addEventListener('keyup', (e) => {
        const { key } = e;
        if (key === ',') {
            createTag(tagInput.value.substring(0, tagInput.value.length - 1));
        }
    });

    button.addEventListener('click', (e) => {
        createTag(tagInput.value);
    });


    const createTagMod = (tagValue) => {
        const value = tagValue.trim();

        if (value === '' || tagsMod.includes(value)) return;

        const tag = document.createElement('span');
        tag.setAttribute('class', 'tag');

        const valor = document.createElement('span');
        valor.setAttribute('class', 'valor');
        valor.innerHTML = value;
        tag.appendChild(valor);

        const close = document.createElement('span');
        close.setAttribute('class', 'remove-tag');
        close.innerHTML = '&#10006;';
        close.onclick = handleRemoveTagMod;

        tag.appendChild(close);
        tagContainerMod.appendChild(tag);
        tagsMod.push(tag);

        tagInputMod.value = '';
        tagInputMod.focus();
    };

    const handleRemoveTagMod = (e) => {
        const indexEtiqueta = tagsMod.findIndex(function(elemento, i){
            if(elemento.childNodes[0].innerHTML == e.target.parentElement.childNodes[0].innerHTML){
                return true;
            }
        });
        e.target.parentElement.remove()
        if(indexEtiqueta > -1){
            tagsMod.splice(indexEtiqueta, 1);
        }
    };

    tagInputMod.addEventListener('keyup', (e) => {
        const { key } = e;
        if (key === ',') {
            createTagMod(tagInputMod.value.substring(0, tagInputMod.value.length - 1));
        }
    });

    buttonMod.addEventListener('click', (e) => {
        createTagMod(tagInputMod.value);
    });

    $('#formularioCrearUsuario').submit(function (e) {
        if($('#formularioCrearUsuario #etiquetas').length == 0){
            let etiquetas = "";
            $.each(tags, function (i, value) {
                etiquetas += `${value.childNodes[0].innerHTML},`;
                if(etiquetas.length > 0 && tags.length - 1 == i){
                    etiquetas = etiquetas.slice(0, -1);
                }
            });
            $('#formularioCrearUsuario').append(
                $('<input>').attr('name', 'etiquetas').attr('id', 'etiquetas').attr('type', 'hidden')
                .val(etiquetas)
            );
        }
    });

    $('#formularioModificarUsuario').submit(function (e) {
        if($('#formularioModificarUsuario #etiquetas').length == 0){
            let etiquetas = "";
            $.each(tagsMod, function (i, value) {
                etiquetas += `${value.childNodes[0].innerHTML},`;
                if(etiquetas.length > 0 && tagsMod.length - 1 == i){
                    etiquetas = etiquetas.slice(0, -1);
                }
            });
            $('#formularioModificarUsuario').append(
                $('<input>').attr('name', 'etiquetas').attr('id', 'etiquetas').attr('type', 'hidden')
                .val(etiquetas)
            );
        }
    });



    </script>
@endsection
