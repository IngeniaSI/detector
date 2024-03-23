@extends('Pages.plantilla')

@section('tittle')
    Usuarios del Sistema
@endsection

@section('cuerpo')
     <!-- Modal Agregar Usuario -->
    <div class="modal fade" id="AgregarModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"> Agregar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            {{-- FORMULARIO DE AGREGAR USUARIO --}}
                        <form id="formularioCrearUsuario" action="{{route('crudUsuario.crear')}}" method="post" style=" @if (!session()->has('formularioCrearErrores')) display:none; @endif ">

                        @csrf
                        <h4>Nombre</h4>
                        <input type="text" name="nombre" class="form-control" value="{{old('nombre')}}" minlength="3" maxlength="255">
                        @error('nombre')
                        <h5>{{$message}}</h5>
                        @enderror
                        <h4>Apellido paterno</h4>
                        <input type="text" name="apellido_paterno" class="form-control" value="{{old('apellido_paterno')}}" minlength="3" maxlength="255">
                        @error('apellido_paterno')
                        <h5>{{$message}}</h5>
                        @enderror
                        <h4>Apellido materno</h4>
                        <input type="text" name="apellido_materno" class="form-control" value="{{old('apellido_materno')}}" minlength="3" maxlength="255">
                        @error('apellido_materno')
                        <h5>{{$message}}</h5>
                        @enderror
                        <h4>Correo</h4>
                        <input type="email" name="correo" value="{{old('correo')}}" class="form-control" minlength="3" maxlength="255">
                        @error('correo')
                        <h5>{{$message}}</h5>
                        @enderror
                        <h4>Contraseña</h4>
                        <input type="password" class="contraseniaRandom" class="form-control" name="contrasenia" value="{{old('contrasenia')}}" minlength="3" maxlength="255">
                        @error('contrasenia')
                        <h5>{{$message}}</h5>
                        @enderror
                        <br>
                        <button class="botonRevelarContrasenia btn btn-info" type="button">Revelar contraseña</button>
                        <button class="botonGenerarClaveRandom btn btn-warning" type="button">Generar nueva contraseña</button>
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
                       
                        </div>
                        
                       
            </div>
            <div class="modal-footer">
            <button class="btn btn-primary">Crear</button>
            </form>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
    </div>
     <!-- Modal Modificar Usuario -->
     <div class="modal fade" id="ModificarModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"> Modificar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            {{-- FORMULARIO DE MODIFICAR USUARIO --}}
        <form id="formularioModificarUsuario" action="@if (session()->has('formularioModificarErrores')) {{route('crudUsuario.editar', session('usuarioAModificar'))}} @endif " method="post" style=" @if (!session()->has('formularioModificarErrores')) display:none; @endif ">
            
        @csrf
            <h4>Nombre</h4>
            <input type="text" class="form-control" id="modificarNombre" name="nombre" value="{{old('nombre')}}" minlength="3" maxlength="255">
            @error('nombre')
            <h5>{{$message}}</h5>
            @enderror
            <h4>Apellido paterno</h4>
            <input type="text" class="form-control"  id="modificarApellidoPaterno" name="apellido_paterno" value="{{old('apellido_paterno')}}" minlength="3" maxlength="255">
            @error('apellido_paterno')
            <h5>{{$message}}</h5>
            @enderror
            <h4>Apellido materno</h4>
            <input type="text" class="form-control"  id="modificarApellidoMaterno" name="apellido_materno" value="{{old('apellido_materno')}}" minlength="3" maxlength="255">
            @error('apellido_materno')
            <h5>{{$message}}</h5>
            @enderror
            <h4>Correo</h4>
            <input type="email" class="form-control"  id="modificarCorreo" name="correo" value="{{old('correo')}}" minlength="3" maxlength="255">
            @error('correo')
            <h5>{{$message}}</h5>
            @enderror
            <h4>Contraseña</h4>
            <input type="password" class="form-control"  class="contraseniaRandom" name="contrasenia" value="{{old('contrasenia')}}" minlength="3" maxlength="255">
            @error('contrasenia')
            <h5>{{$message}}</h5>
            @enderror
            <br>
            <button class="botonRevelarContrasenia btn btn-info" type="button">Revelar contraseña</button>
            <button class="botonGenerarClaveRandom btn btn-warning" type="button">Generar nueva contraseña</button>
            <h4>Roles</h4>
            <select id="modificarRolUsuario" class="form-control"  name="rolUsuario" @disabled(old('rolUsuario') == null)>
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
              
            </div>
            <div class="modal-footer">
            <button class="btn btn-primary">Modificar</button>
            </form>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
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
               
                <table id="tablaUsuarios" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                                <th>Correo</th>
                                <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                           
                    </tbody>
                </table>
                <table id="tablaUsuarios2" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%" hidden>
                    <thead>
                        <tr>
                                <th>Correo</th>
                                <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                           
                    </tbody>
                </table>

        



{{-- PASAR LIBRERIAS A PLANTILLA --}}
<script src="/js/generador-contrasenias.js" text="text/javascript"></script>

</main>
<footer class="py-4 bg-light mt-auto">
    <div class="container-fluid px-4">
        <div class="d-flex align-items-center justify-content-between small">
            <div class="text-muted">Copyright &copy; IngeniaIS 2024</div>
            
        </div>
    </div>
</footer>
</div>
</div>

@error('errorBorrar')
<div class="alert alert-warning" role="alert">
<h5>{{$message}}</h5>
</div>
@enderror

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
    var table = $('#tablaUsuarios').DataTable( {
    lengthChange: true,
    language: {
    url: 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json',
    },
    buttons: [ 'copy', 'excel', 'csv', 'pdf', 'colvis' ]
    } );
    
    table.buttons().container()
    .appendTo( '#example_wrapper .col-md-6:eq(0)' );
    }
    
    
    );
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
            var nuevaFila = $('<tr>').append(
                $('<td>').text(elemento.email),
                $('<td>').append(
                    $('<button>').attr('id', 'btnModificarUsuario_' + elemento.id).attr('class', 'btn btn-success').html('<i class="fas fa-edit me-1"></i>Editar'),
                    $('<form>').attr('action', "{{url('/')}}/gestor-usuarios/borrar-usuario-" + elemento.id).attr('method','post').append(
                        $('<input>').attr('type', 'hidden').attr('name', '_token').val('{{csrf_token()}}'),
                        $('<button>').attr('id', 'btnBorrarCuenta_' + elemento.id).attr('class', 'btn btn-danger').html('<i class="fas fa-close me-1"></i>Borrar')
                    )
                )
            );
            $('#tablaUsuarios2 tbody').append(nuevaFila);
            $('#tablaUsuarios').DataTable().row.add([
                elemento.email, '<button id="btnModificarUsuario_'+elemento.id+'" class ="btn btn-success" data-bs-toggle="modal" data-bs-target="#ModificarModal" ><i class="fas fa-edit me-1"></i>Editar</button><a onclick="clickBorrar('+elemento.id+')" type="button" class="btn btn btn-danger"><i class="fas fa-close me-1"></i>Borrar</a>'
            ]).draw();
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
        if(response[1] == 'SUPER_ADMINISTRADOR'){
            $('#modificarRolUsuario').prop('disabled', true);
        }
        else{
            $('#modificarRolUsuario').prop('disabled', false);
        }
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

    function clickBorrar(id) {

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
            $("#btnBorrarCuenta_"+id).click()
        }
        });
       
        
    }
    </script>
@endsection
