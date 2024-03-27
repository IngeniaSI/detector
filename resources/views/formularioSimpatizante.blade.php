@extends('Pages.plantilla')

@section('tittle')
    Agregar Persona
@endsection

@section('cuerpo')

<BR>
<div class="card" class="m-3">
    <div class="card-header">
        <h3>Agregar Persona</h3>
    </div>
    <div class="card-body">
        {{-- FORMULARIO DE AGREGAR USUARIO --}}
        <form id="formularioAgregarSimpatizante" action="{{route('agregarSimpatizante.agregandoSimpatizante')}}" method="post" style="">
            @csrf
            <div class="container">
                <br><br>
                <h3>Datos Generales</h3>
                <div class="row">
                    <div class="col">
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
                    </div>
                    <div class="col">
                        <h4>Correo</h4>
                        <input type="email" class="form-control" name="correo" value="{{old('correo')}}" minlength="3" maxlength="255">
                        @error('correo')
                            <h5>{{$message}}</h5>
                        @enderror
                    </div>
                    <div class="col">
                        <h4>Telefono Celular</h4>
                        <input type="text" class="form-control" name="telefonoCelular" value="{{old('telefonoCelular')}}" minlength="10" maxlength="20">
                        @error('telefonoCelular')
                            <h5>{{$message}}</h5>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <h4>Telefono Fijo</h4>
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
                        <h4>Escolaridad</h4>
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
                        <h4>Fecha de Nacimiento</h4>
                        <input type="date" class="form-control" name="fechaNacimiento" value="{{old('fechaNacimiento')}}" min="{{date('Y-m-d', strtotime('-100 years'))}}" max="{{date('Y-m-d', strtotime('-18 years'))}}">
                        @error('fechaNacimiento')
                            <h5>{{$message}}</h5>
                        @enderror
                    </div>
                    <div class="col">
                        <h4>Clave Electoral</h4>
                        <input type="text" class="form-control" id="claveElectoral" name="claveElectoral" value="{{old('claveElectoral')}}" minlength="18" maxlength="18" placeholder="ABCDEF12345678B123">
                        @error('claveElectoral')
                            <h5>{{$message}}</h5>
                        @enderror
                    </div>
                    <div class="col">
                        <h4>CURP</h4>
                        <input type="text" class="form-control" id="curp" name="curp" value="{{old('curp')}}" minlength="18" maxlength="18" placeholder="ABCD123456HBCDEF12">
                        @error('curp')
                            <h5>{{$message}}</h5>
                        @enderror
                    </div>
                </div>
                <br>
                @error('errorValidacion')
                    <h5>{{$message}}</h5>
                @enderror
                <br>
                <div class="row">
                    <h3>Dirección</h3>
                    <div class="col">
                        <h4>Calle</h4>
                        <input type="text" class="form-control" name="calle" value="{{old('calle')}}">
                        @error('calle')
                            <h5>{{$message}}</h5>
                        @enderror
                    </div>
                    <div class="col">
                        <h4>Número Externo</h4>
                        <input type="number" class="form-control" name="numeroExterior" value="{{old('numeroExterior')}}">
                        @error('numeroExterior')
                            <h5>{{$message}}</h5>
                        @enderror
                    </div>
                    <div class="col">
                        <h4>Número Interno</h4>
                        <input type="number" class="form-control" name="numeroInterior" value="{{old('numeroInterior')}}">
                        @error('numeroInterior')
                            <h5>{{$message}}</h5>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <h4>Colonia</h4>
                        <input type="text" class="form-control" name="colonia" value="{{old('colonia')}}">
                        @error('colonia')
                            <h5>{{$message}}</h5>
                        @enderror
                    </div>
                    <div class="col">
                        <h4>Municipio o Delegación</h4>
                        <input type="text" class="form-control" name="municipio" value="{{old('municipio')}}">
                        @error('municipio')
                            <h5>{{$message}}</h5>
                        @enderror
                    </div>
                    <div class="col">
                        <h4>Código Postal</h4>
                        <input type="number" class="form-control" name="codigoPostal" value="{{old('codigoPostal')}}">
                        @error('codigoPostal')
                            <h5>{{$message}}</h5>
                        @enderror
                    </div>
                </div>
                <br><br><br><br>
                <div class="row">
                    <h3>Información</h3>
                    <div class="col">
                        <h4>Folio</h4>
                        <input type="number" class="form-control" name="folio" value="{{old('folio')}}">
                        @error('folio')
                            <h5>{{$message}}</h5>
                        @enderror
                    </div>
                    <div class="col">
                        <h4>Entidad Federativa</h4>
                        <input type="number" class="form-control" name="entidadFederativa" value="{{old('entidadFederativa')}}">
                        @error('entidadFederativa')
                            <h5>{{$message}}</h5>
                        @enderror
                    </div>
                    <div class="col">
                        <h4>Distrito Federal</h4>
                        <input type="number" class="form-control" name="distritoFederal" value="{{old('distritoFederal')}}">
                        @error('distritoFederal')
                            <h5>{{$message}}</h5>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <h4>Distrito local</h4>
                        <input type="text" class="form-control" name="distritoLocal" value="{{old('distritoLocal')}}">
                        @error('distritoLocal')
                            <h5>{{$message}}</h5>
                        @enderror
                    </div>
                    <div class="col">
                        <h4>Sección</h4>
                        <input type="number" class="form-control" name="seccion" value="{{old('seccion')}}">
                        @error('seccion')
                            <h5>{{$message}}</h5>
                        @enderror
                    </div>
                    <div class="col">
                        <h4>Afiliado</h4>
                        <select class="form-control" name="esAfiliado">
                            <option select>No</option>
                            <option>Si</option>
                        </select>
                        @error('esAfiliado')
                            <h5>{{$message}}</h5>
                        @enderror
                    </div>
                    <div class="col">
                        <h4>Simpatizantes</h4>
                        <select class="form-control" name="esSimpatizante">
                            <option select>No</option>
                            <option>Si</option>
                            <option>Talvez</option>
                        </select>
                        @error('esSimpatizante')
                            <h5>{{$message}}</h5>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <h4>Programas</h4>
                        <select class="form-control" name="programa">
                            <option select>Ninguno</option>
                            <option>Programa 1</option>
                            <option>Programa 2</option>
                        </select>
                        @error('programa')
                            <h5>{{$message}}</h5>
                        @enderror
                    </div>
                    <div class="col">
                        <h4>Funciones</h4>
                        <select class="form-control" name="funciones">
                            <option select>Ninguno</option>
                            <option>Medicina</option>
                            <option>Lentes</option>
                        </select>
                        @error('funciones')
                            <h5>{{$message}}</h5>
                        @enderror
                    </div>
                    <div class="col">
                        <h4>Etiquetas(Opcional)</h4>
                        <input type="text" class="form-control" name="etiquetas">
                        @error('etiquetas')
                            <h5>{{$message}}</h5>
                        @enderror
                    </div>
                    <div class="col">
                        <h4>Fecha de registro</h4>
                        <input type="date" class="form-control" name="fechaRegistro" value="{{old('fechaRegistro')}}" min="{{date('Y-m-d', strtotime('-100 years'))}}" max="{{date('Y-m-d')}}">
                        @error('fechaRegistro')
                            <h5>{{$message}}</h5>
                        @enderror
                    </div>
                </div>
                <br><br>
                <center>
                    <h4>Coordenadas</h4>
                    <input type="hidden" id="coordenadas" name="coordenadas">
                    <input type="text" class="col-3" id="cordenada" class="form-control" disabled>
                </center>
                <center>
                    <div id="map" style="width:450px;height:300px"></div>
                    @error('coordenadas')
                            <h5>{{$message}}</h5>
                    @enderror
                </center>
                <div class="row">
                    <div class="col">
                        <h4>Comentarios</h4>
                        <div class="form-group">
                            <textarea class="form-control" rows="5" id="comment" name="observaciones"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <br><br>
            <div>
                <center>
                    <button class="btn btn-primary">Agregar</button>
                    <button class="btn btn-danger" type="button" class="cerrarFormulario">Limpiar</button>
                    <a href="{{route('crudSimpatizantes.index')}}">
                        <button class="btn btn-success" type="button">Tabla Personas</button>
                    </a>
                </center>
            </div>
        </div>
    </form>
</div>


    </script>
@endsection

@section('scripts')
  {{-- PASAR LIBRERIAS A PLANTILLA --}}
  {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css"> --}}

  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDg60SDcmNRPnG1tzZNBBGFx02cW2VkWWQ&callback=initMap&v=weekly" defer></script>

  <script text="text/javascript">
    function initMap() {
        var marker;
        var marker2;
        const myLatLng = { lat: 24.123954, lng: - 110.311664 };

        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 17,
            center: myLatLng,
            title: "Ubicación ",
        });

        google.maps.event.addListener(map, 'click', function (event) {
            placeMarker(event.latLng);
            document.getElementById("cordenada").value = event.latLng.lat() + ", " + event.latLng.lng();
            document.getElementById("coordenadas").value = event.latLng.lat() + "," + event.latLng.lng();
        });



        function placeMarker(location) {
            if (marker == undefined) {
                marker = new google.maps.Marker({
                    position: location,
                    map: map,
                    title: "Ubicación",
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 15,
                        fillColor: "#F00",
                        fillOpacity: 0.4,
                        strokeWeight: 0.4,
                    },
                    animation: google.maps.Animation.DROP,
                });
                marker2 = new google.maps.Marker({
                    position: location,
                    map: map,
                    title: "Ubicación",
                    animation: google.maps.Animation.DROP,
                });
            }
            else {
                marker.setPosition(location);
                marker2.setPosition(location);
            }
            map.setCenter(location);

        }

    }

    window.initMap = initMap;


    //FIN MAPA



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

    $('#curp').on('input', soloMayusculas);
    $('#claveElectoral').on('input', soloMayusculas);
    function soloMayusculas(){
        $(this).val($(this).val().toUpperCase());
    }
    </script>
@endsection

