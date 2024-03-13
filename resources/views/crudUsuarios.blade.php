<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>crud usuarios</title>
    <style>
        table {
          font-family: arial, sans-serif;
          border-collapse: collapse;
          width: 100%;
        }

        td, th {
          border: 1px solid #dddddd;
          text-align: left;
          padding: 8px;
        }

        tr:nth-child(even) {
          background-color: #dddddd;
        }
        </style>
</head>
<body>
    {{-- FORMULARIO DE AGREGAR USUARIO --}}
    <form id="formularioCrearUsuario" action="{{route('crudUsuario.crear')}}" method="post" style=" @if (!session()->has('formularioCrearErrores')) display:none; @endif ">
        <h3>Crear usuario</h3>
        @csrf
        <h4>Nombre</h4>
        <input type="text" name="nombre" value="{{old('nombre')}}">
        @error('nombre')
            <h5>{{$message}}</h5>
        @enderror
        <h4>Apellido paterno</h4>
        <input type="text" name="apellido_paterno" value="{{old('apellido_paterno')}}">
        @error('apellido_paterno')
            <h5>{{$message}}</h5>
        @enderror
        <h4>Apellido materno</h4>
        <input type="text" name="apellido_materno" value="{{old('apellido_materno')}}">
        @error('apellido_materno')
            <h5>{{$message}}</h5>
        @enderror
        <h4>Correo</h4>
        <input type="email" name="correo" value="{{old('correo')}}">
        @error('correo')
            <h5>{{$message}}</h5>
        @enderror
        <h4>Contraseña</h4>
        <input type="password" name="contrasenia" value="{{old('contrasenia')}}">
        @error('contrasenia')
            <h5>{{$message}}</h5>
        @enderror
        <h4>Repetir Contraseña</h4>
        <input type="password" name="repetirContrasenia" value="{{old('repetirContrasenia')}}">
        @error('repetirContrasenia')
            <h5>{{$message}}</h5>
        @enderror
        <h4>Roles</h4>
        <select name="rolUsuario">
            <option value="-1">Selecciona un rol</option>
            @foreach ($roles as $rol)
                <option value="{{$rol->name}}">{{str_replace('_', ' ', $rol->name)}}</option>
            @endforeach
        </select>
        @error('rolUsuario')
            <h5>{{$message}}</h5>
        @enderror
        @error('errorValidacion')
            <h5>{{$message}}</h5>
        @enderror
        <div>
            <button>Crear</button>
            <button type="button" class="cerrarFormulario">Cerrar</button>
        </div>
        <hr>
    </form>
    {{-- FORMULARIO DE MODIFICAR USUARIO --}}
    <form id="formularioModificarUsuario" action="" method="post" style=" @if (!session()->has('formularioModificarErrores')) display:none; @endif ">
        <h3>Modificar usuario</h3>
        @csrf
        <h4>Crear usuario</h4>
        @csrf
        <h4>Nombre</h4>
        <input type="text" id="modificarNombre" name="nombre" value="{{old('nombre')}}">
        @error('nombre')
            <h5>{{$message}}</h5>
        @enderror
        <h4>Apellido paterno</h4>
        <input type="text" id="modificarApellidoPaterno" name="apellido_paterno" value="{{old('apellido_paterno')}}">
        @error('apellido_paterno')
            <h5>{{$message}}</h5>
        @enderror
        <h4>Apellido materno</h4>
        <input type="text" id="modificarApellidoMaterno" name="apellido_materno" value="{{old('apellido_materno')}}">
        @error('apellido_materno')
            <h5>{{$message}}</h5>
        @enderror
        <h4>Correo</h4>
        <input type="email" id="modificarCorreo" name="correo" value="{{old('correo')}}">
        @error('correo')
            <h5>{{$message}}</h5>
        @enderror
        <h4>Contraseña</h4>
        <input type="password" name="contrasenia" value="{{old('contrasenia')}}">
        @error('contrasenia')
            <h5>{{$message}}</h5>
        @enderror
        <button type="button">Generar nueva contraseña</button>
        <h4>Roles</h4>
        <select id="modificarRolUsuario" name="rolUsuario">
            @foreach ($roles as $rol)
                <option value="{{$rol->name}}">{{str_replace('_', ' ', $rol->name)}}</option>
            @endforeach
        </select>
        @error('rolUsuario')
            <h5>{{$message}}</h5>
        @enderror
        @error('errorValidacion')
            <h5>{{$message}}</h5>
        @enderror
        <div>
            <button>Modificar</button>
            <button type="button" class="cerrarFormulario">Cerrar</button>
        </div>
        <hr>
    </form>
    <button class="btnCrearUsuario">Crear usuario</button>
    {{-- TABLA DE USUARIOS --}}
    <table id="tablaUsuarios">
        <thead>
            <th>Correo</th>
            <th>Acciones</th>
        </thead>
        <tbody>

        </tbody>
    </table>
    <hr>
    <form action="{{route('logout')}}" method="post">
        @csrf
        <button>Cerrar sesion</button>
    </form>
    {{-- PASAR LIBRERIAS A PLANTILLA --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script text="text/javascript">
    // FUNCION PARA CARGAR TABLA DE USUARIOS
    $(document).ready(function () {
        $.when(
            $.ajax({
                type: "get",
                url: "{{route('crudUsuario.todos')}}",
                data: [],
                contentType: "application/x-www-form-urlencoded",
                success: function (response) {
                    $.each(response, function (index, elemento) {
                        var nuevaFila = $('<tr>').append(
                            $('<td>').text(elemento.email),
                            $('<td>').append(
                                $('<button>').attr('id', 'btnModificarUsuario_' + elemento.id).text('Modificar'),
                                $('<form>').attr('action', "{{url('/')}}/gestor-usuarios/borrar-usuario-" + elemento.id).attr('method','post').append(
                                    $('<input>').attr('type', 'hidden').attr('name', '_token').val('{{csrf_token()}}'),
                                    $('<button>').text('Borrar')
                                )
                            )
                        );
                        $('#tablaUsuarios tbody').append(nuevaFila);
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
    });

    // FUNCION CARGAR FORMULARIO MODIFICAR
    function  cargarFormularioModificar(e) {
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
                    $('#modificarRolUsuario').val(response[1]);

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
        $('.cerrarFormulario').click(
            function (e) {
            $('#formularioCrearUsuario')[0].reset();
            $('#formularioModificarUsuario')[0].reset();
            $('#formularioCrearUsuario').css('display', 'none');
            $('#formularioModificarUsuario').css('display', 'none');
        }
    );
    </script>
</body>
</html>
