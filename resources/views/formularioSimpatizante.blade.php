@extends('Pages.plantilla')

@section('tittle')
    Agregar simpatizante
@endsection

@section('cuerpo')
<BR>
    {{-- FORMULARIO DE AGREGAR USUARIO --}}
    <form id="formularioAgregarSimpatizante" action="{{route('agregarSimpatizante.agregandoSimpatizante')}}" method="post" style="">
        <div class="container">

            <div class="row">
              <div class="col">
                @csrf
                <h4>Nombre(s)</h4>
                <input type="text" class="form-control" name="nombre" value="{{old('nombre')}}" minlength="3" maxlength="255">
                @error('nombre')
                    <h5>{{$message}}</h5>
                @enderror
              </div>
              <div class="col">
                <h4>Apellido paterno</h4>
                <input type="text" class="form-control" name="apellido_paterno" value="{{old('apellido_paterno')}}" minlength="3" maxlength="255">
                @error('apellido_paterno')
                    <h5>{{$message}}</h5>
                @enderror
              </div>
              <div class="col">
                <h4>Apellido materno</h4>
                <input type="text" class="form-control" name="apellido_materno" value="{{old('apellido_materno')}}" minlength="3" maxlength="255">
                @error('apellido_materno')
                    <h5>{{$message}}</h5>
                @enderror
              </div>
            </div>
            <div class="row">
                <div class="col">
                    <h4>Genero</h4>
                    <h5><input type="radio" name="genero" value="HOMBRE"> Hombre <input type="radio" name="genero" value="MUJER"> Mujer </h5>
                    @error('genero')
                        <h5>{{$message}}</h5>
                    @enderror
                    <h5></h5>
                </div>
                <div class="col">
                    <h4>Correo</h4>
                    <input type="email" class="form-control" name="correo" value="{{old('correo')}}" minlength="3" maxlength="255">
                    @error('correo')
                        <h5>{{$message}}</h5>
                    @enderror
                </div>
                <div class="col">
                    <h4>telefono celular</h4>
                    <input type="text" class="form-control" name="telefonoCelular" value="{{old('telefonoCelular')}}" minlength="10" maxlength="20">
                    @error('telefonoCelular')
                        <h5>{{$message}}</h5>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <h4>telefono fijo</h4>
                    <input type="text" class="form-control" name="telefonoFijo" value="{{old('telefonoFijo')}}" minlength="10" maxlength="20">
                    @error('telefonoFijo')
                        <h5>{{$message}}</h5>
                    @enderror
                </div>
                <div class="col">
                    <h4>Facebook</h4>
                    <input type="text" class="form-control" name="facebook" value="{{old('facebook')}}" minlength="3" maxlength="255">
                    @error('facebook')
                        <h5>{{$message}}</h5>
                    @enderror
                </div>
                <div class="col">
                    <h4>escolaridad</h4>
                    <select name="escolaridad" class="form-control" value="{{old('escolaridad')}}">
                        <option value="PRIMARIA">Primaria</option>
                        <option value="SECUNDARIA">Secundaria</option>
                        <option value="PREPARATORIA">Preparatoria</option>
                        <option value="PROFESIONAL">Profesional</option>
                        <option value="MAESTRIA">Maestria</option>
                    </select>
                    @error('escolaridad')
                        <h5>{{$message}}</h5>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <h4>fecha de nacimiento</h4>
                    <input type="date" class="form-control" name="fechaNacimiento" value="{{old('fechaNacimiento')}}" min="{{date('Y-m-d', strtotime('-100 years'))}}" max="{{date('Y-m-d')}}">
                    @error('fechaNacimiento')
                        <h5>{{$message}}</h5>
                    @enderror
                    @error('errorValidacion')
                        <h5>{{$message}}</h5>
                    @enderror
                </div>
                <div class="col">
                    <h4>Clave electoral</h4>
                    <input type="text" class="form-control" name="claveElectoral" value="{{old('claveElectoral')}}" minlength="18" maxlength="18">
                    @error('claveElectoral')
                        <h5>{{$message}}</h5>
                    @enderror
                </div>
                <div class="col">
                    <h4>CURP</h4>
                    <input type="text" class="form-control" name="curp" value="{{old('curp')}}" minlength="18" maxlength="18">
                    @error('curp')
                        <h5>{{$message}}</h5>
                    @enderror
                </div>
            </div>


        </div>

        <div>
            <center>
            <button class="btn btn-primary">Agregar</button>
            <button class="btn btn-danger" type="button" class="cerrarFormulario">Limpiar</button>
            <a href="{{route('crudSimpatizantes.index')}}">
                <button class="btn btn-success" type="button">Tabla Personas</button>
            </a>
            </center>
        </div>
    </form>


    </script>
@endsection

@section('scripts')
  {{-- PASAR LIBRERIAS A PLANTILLA --}}
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script text="text/javascript">
    <script>


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
@endsection

