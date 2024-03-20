<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Agregar simpatizante</title>
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
    @if (session()->has('mensaje'))
        <script>
            alert('{{session("mensaje")}}');
        </script>
    @endif
    {{-- FORMULARIO DE AGREGAR USUARIO --}}
    <form id="formularioAgregarSimpatizante" action="{{route('agregarSimpatizante.agregandoSimpatizante')}}" method="post" style="">
        <h3>Agregar simpatizante</h3>
        @csrf
        <h4>Nombre(s)</h4>
        <input type="text" name="nombre" value="{{old('nombre')}}" minlength="3" maxlength="255">
        @error('nombre')
            <h5>{{$message}}</h5>
        @enderror
        <h4>Apellido paterno</h4>
        <input type="text" name="apellido_paterno" value="{{old('apellido_paterno')}}" minlength="3" maxlength="255">
        @error('apellido_paterno')
            <h5>{{$message}}</h5>
        @enderror
        <h4>Apellido materno</h4>
        <input type="text" name="apellido_materno" value="{{old('apellido_materno')}}" minlength="3" maxlength="255">
        @error('apellido_materno')
            <h5>{{$message}}</h5>
        @enderror
        <h4>Genero</h4>
        <h5>Hombre</h5>
        <input type="radio" name="genero" value="HOMBRE">
        <h5>Mujer</h5>
        <input type="radio" name="genero" value="MUJER">
        @error('genero')
            <h5>{{$message}}</h5>
        @enderror
        <h4>Correo</h4>
        <input type="email" name="correo" value="{{old('correo')}}" minlength="3" maxlength="255">
        @error('correo')
            <h5>{{$message}}</h5>
        @enderror
        <h4>telefono celular</h4>
        <input type="text" name="telefonoCelular" value="{{old('telefonoCelular')}}" minlength="10" maxlength="20">
        @error('telefonoCelular')
            <h5>{{$message}}</h5>
        @enderror
        <h4>telefono fijo</h4>
        <input type="text" name="telefonoFijo" value="{{old('telefonoFijo')}}" minlength="10" maxlength="20">
        @error('telefonoFijo')
            <h5>{{$message}}</h5>
        @enderror
        <h4>Facebook</h4>
        <input type="text" name="facebook" value="{{old('facebook')}}" minlength="10" maxlength="255">
        @error('facebook')
            <h5>{{$message}}</h5>
        @enderror
        <h4>escolaridad</h4>
        <select name="escolaridad" value="{{old('escolaridad')}}">
            <option value="PRIMARIA">Primaria</option>
            <option value="SECUNDARIA">Secundaria</option>
            <option value="PREPARATORIA">Preparatoria</option>
            <option value="PROFESIONAL">Profesional</option>
            <option value="MAESTRIA">Maestria</option>
        </select>
        @error('escolaridad')
            <h5>{{$message}}</h5>
        @enderror
        <h4>fecha de nacimiento</h4>
        <input type="date" name="fechaNacimiento" value="{{old('fechaNacimiento')}}" min="{{date('Y-m-d', strtotime('-100 years'))}}" max="{{date('Y-m-d')}}">
        @error('fechaNacimiento')
            <h5>{{$message}}</h5>
        @enderror
        @error('errorValidacion')
            <h5>{{$message}}</h5>
        @enderror
        <div>
            <button>Agregar</button>
            <button type="button" class="cerrarFormulario">Limpiar</button>
            <a href="{{route('crudSimpatizantes.index')}}">
                <button type="button">Crud simpatizantes</button>
            </a>
        </div>
        <hr>
    </form>

    {{-- PASAR LIBRERIAS A PLANTILLA --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script text="text/javascript">

    // FUNCION PARA CARGAR TABLA DE USUARIOS
    $(document).ready(function () {
        $.when(
            $.ajax({
                type: "get",
                url: "{{route('crudSimpatizantes.inicializar')}}",
                data: [],
                contentType: "application/x-www-form-urlencoded",
                success: function (response) {

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



    // FUNCION CERRAR FORMULARIO
        $('.cerrarFormulario').click(
            function (e) {
            $('#formularioAgregarSimpatizante')[0].reset();
        }
    );
    </script>
</body>
</html>
