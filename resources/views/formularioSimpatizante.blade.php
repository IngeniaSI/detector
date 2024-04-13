@extends('Pages.plantilla')

@section('tittle')
    Agregar Persona
@endsection

@section('cuerpo')
<style>
    h4{
        font-weight: 400;
    }

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
                @error('errorValidacion')
                    <div class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                @enderror
                <br>
                <div class="p-4 border rounded-3 bg-secondary bg-opacity-10">
                    <h3>Datos de control</h3>
                    <div class="row row-cols-1 row-cols-sm-3">
                        <div class="col">
                            <h4>Fecha de registro</h4>
                            <input type="date" class="form-control" id="fechaRegistro" name="fechaRegistro" value="{{old('fechaRegistro')}}" min="{{date('Y-m-d', strtotime('-100 years'))}}" max="{{date('Y-m-d')}}">
                            @error('fechaRegistro')
                                <div id="fechaRegistroError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                        <div class="col">
                            <h4>Folio</h4>
                            <input type="number" min="0" maxlength="7" class="form-control" id="folio" name="folio" value="{{old('folio')}}">
                            @error('folio')
                                <div id="folioError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                        <div class="col">
                            <h4>Promotor</h4>
                            <select class="form-control selectToo" id="promotores" name="promotor">
                                <option value="-1" selected>Sin promotor</option>
                            </select>
                            @error('promotor')
                                <div id="promotorError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                    </div>
                </div>
                <br>
                <div class="p-4 border rounded-3 bg-secondary bg-opacity-10">
                    <h3>Datos personales</h3>
                    <div class="row row-cols-1 row-cols-sm-3">
                        <div class="col">
                            <h4>Apellido paterno (*)</h4>
                            <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" value="{{old('apellido_paterno')}}" minlength="3" maxlength="255" onkeydown="return /[a-z, ]/i.test(event.key)"
    onblur="if (this.value == '') {this.value = '';}"
    onfocus="if (this.value == '') {this.value = '';}">
                            @error('apellido_paterno')
                                <div id="apellidoPaternoError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                        <div class="col">
                            <h4>Apellido materno (*)</h4>
                            <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" value="{{old('apellido_materno')}}" minlength="3" maxlength="255" onkeydown="return /[a-z, ]/i.test(event.key)"
    onblur="if (this.value == '') {this.value = '';}"
    onfocus="if (this.value == '') {this.value = '';}">
                            @error('apellido_materno')
                                <div id="apellidoMaternoError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                        <div class="col">
                            <h4>Nombre(s) (*)</h4>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="{{old('nombre')}}" minlength="3" maxlength="255" onkeydown="return /[a-z, ]/i.test(event.key)"
    onblur="if (this.value == '') {this.value = '';}"
    onfocus="if (this.value == '') {this.value = '';}">
                            @error('nombre')
                                <div id="nombresError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                    </div>
                    <br>
                    <div class="row row-cols-1 row-cols-sm-3">
                        <div class="col">
                            <h4>Genero (*)</h4>
                            <select name="genero" class="form-control" name="rangoEdad">
                                <option value="SIN ESPECIFICAR">SIN ESPECIFICAR</option>
                                <option value="HOMBRE">HOMBRE</option>
                                <option value="MUJER">MUJER</option>
                            </select>
                            
                        </div>
                        <div class="col">
                            <h4>Fecha de Nacimiento</h4>
                            <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento" value="{{old('fechaNacimiento')}}" min="{{date('Y-m-d', strtotime('-100 years'))}}" max="{{date('Y-m-d', strtotime('-18 years'))}}">
                            @error('fechaNacimiento')
                                <div id="fechaNacimientoError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                        <div class="col">
                            <h4>Rango de edad</h4>
                            <select class="form-control" name="rangoEdad">
                                <option value="23">18-28</option>
                                <option value="34">29-39</option>
                                <option value="45">40-49</option>
                                <option value="55">50-69</option>
                                <option value="74">69-adelante</option>
                            </select>
                            @error('rangoEdad')
                                <div id="rangoEdadError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                    </div>
                    <div class="row row-cols-1 row-cols-sm-3">
                        <div class="col">
                            <h4>Escolaridad</h4>
                            <select class="form-control" name="escolaridad">
                                <option>SIN ESTUDIOS</option>
                                <option>PRIMARIA</option>
                                <option>SECUNDARIA</option>
                                <option selected>PREPARATORIA</option>
                                <option>UNIVERSIDAD</option>
                                <option>MAESTRIA</option>
                                <option>DOCTORADO</option>
                            </select>
                            @error('escolaridad')
                                <div id="escolaridadError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                        <div class="col">
                        </div>
                        <div class="col">

                        </div>
                    </div>
                </div>
                <br>
                <div class="p-4 border rounded-3 bg-secondary bg-opacity-10">
                    <h3>Datos de contacto</h3>
                    <div class="row row-cols-1 row-cols-sm-3">
                        <div class="col">
                            <h4>Telefono Celular (*)</h4>
                            <input type="number" class="form-control" id="telefonoCelular" name="telefonoCelular" value="{{old('telefonoCelular')}}" minlength="10" maxlength="12">
                            @error('telefonoCelular')
                                <div id="telefonoCelularError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                        <div class="col">
                            <h4>Telefono Fijo</h4>
                            <input type="number" class="form-control" id="telefonoFijo" name="telefonoFijo" value="{{old('telefonoFijo')}}" minlength="10" maxlength="12">
                            @error('telefonoFijo')
                                <div id="telefonoFijoError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                        <div class="col">
                            <h4>Correo electronico (*)</h4>
                            <input type="email" style="text-transform: uppercase; color: black"  class="form-control" id="correo" name="correo" value="{{old('correo')}}" minlength="3" maxlength="255">
                            @error('correo')
                                <div id="correoError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                    </div>
                    <br>
                    <div class="row row-cols-1 row-cols-sm-3">
                        <div class="col">
                            <h4>Facebook</h4>
                            <input type="text" class="form-control" id="facebook" name="facebook" value="{{old('facebook')}}" minlength="3" maxlength="255" >
                            @error('facebook')
                                <div id="facebookError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                        <div class="col">

                        </div>
                        <div class="col">

                        </div>
                    </div>
                </div>
                <br>
                <div class="p-4 border rounded-3 bg-secondary bg-opacity-10">
                    <h3>Datos de domicilio</h3>
                    <div class="row row-cols-1 row-cols-sm-3">
                        <div class="col">
                            <h4>Calle (*)</h4>
                            <input type="text" class="form-control" id="calle" name="calle" value="{{old('calle')}}" onkeydown="return /[a-z, ]/i.test(event.key)"
    onblur="if (this.value == '') {this.value = '';}"
    onfocus="if (this.value == '') {this.value = '';}">
                            @error('calle')
                                <div id="calleError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                        <div class="col">
                            <h4>Número Externo (*)</h4>
                            <input type="number" class="form-control" id="numeroExterior" name="numeroExterior" value="{{old('numeroExterior')}}">
                            @error('numeroExterior')
                                <div id="numeroExteriorError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                        <div class="col">
                            <h4>Número Interno</h4>
                            <input type="number" class="form-control" id="numeroInterior" name="numeroInterior" value="{{old('numeroInterior')}}">
                            @error('numeroInterior')
                                <div id="numeroInteriorError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                    </div>
                    <br>
                    <div class="row row-cols-1 row-cols-sm-3">
                        <div class="col">
                            <h4>Código Postal (*)</h4>
                            <input type="number" class="form-control" id="codigoPostal" name="codigoPostal" value="{{old('codigoPostal')}}">
                            @error('codigoPostal')
                            <div id="codigoPostalError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                        <div class="col">
                            <h4>Municipio o Delegación (*)</h4>
                            <select class="form-control selectToo" id="municipios" name="municipio">
                                <option value="0">- - -</option>
                            </select>
                            @error('municipio')
                                <div id="municipioError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                        <div class="col">
                            <h4>Colonia (*)</h4>
                            <select class="form-control selectToo" id="colonias" name="colonia">
                                <option value="0">- - -</option>
                            </select>
                            @error('colonia')
                                <div id="coloniaError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>

                    </div>
                    <br>
                    <h4>¿Donde vive la persona?</h4>
                    <center>
                        <input type="hidden" id="coordenadas" name="coordenadas">
                        <input type="text" class="col-3 d-none" id="cordenada" class="form-control" disabled>
                    </center>
                    <center>
                        <div id="map" class="mx-auto" style="width:100%;height:400px"></div>
                        @error('coordenadas')
                                <div class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                        @enderror
                    </center>
                </div>
                <br>
                <div class="p-4 border rounded-3 bg-secondary bg-opacity-10">
                    <h3>Datos de identificación</h3>
                    <div class="row row-cols-1 row-cols-sm-3">
                        <div class="col">
                            <h4>Clave Electoral</h4>
                            <input type="text" class="form-control" id="claveElectoral" name="claveElectoral" value="{{old('claveElectoral')}}" minlength="18" maxlength="18" placeholder="ABCDEF12345678B123">
                            @error('claveElectoral')
                                <div id="claveElectoralError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                        <div class="col">
                            <h4>CURP</h4>
                            <input type="text" class="form-control" id="curp" name="curp" value="{{old('curp')}}" minlength="18" maxlength="18" placeholder="ABCD123456HBCDEF12">
                            @error('curp')
                                <div id="curpError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                        <div class="col">
                            <div class="d-flex">
                                <h4>Sección</h4>
                                <a class="ms-3" title="Este dato se encuentra en su identificación INE">
                                    <i class="bi bi-exclamation-circle-fill"></i>
                                </a>
                            </div>
                            <select class="form-control selectToo" id="secciones" name="seccion">
                                <option value="0">- - -</option>
                            </select>
                            @error('seccion')
                                <div id="seccionError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                    </div>
                    <br>
                    <div class="row row-cols-1 row-cols-sm-3">
                        <div class="col">
                            <h4>Entidad Federativa</h4>
                            <select class="form-control selectToo" id="entidades" name="entidadFederativa">
                                <option value="0">- - -</option>
                            </select>
                            @error('entidadFederativa')
                                <div id="entidadFederativaError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                        <div class="col">
                            <h4>Distrito Federal</h4>
                            <select class="form-control selectToo" id="distritosFederales" name="distritoFederal">
                                <option value="0">- - -</option>
                            </select>
                            @error('distritoFederal')
                                <div id="distritoFederalError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                        <div class="col">
                            <h4>Distrito local</h4>
                            <select class="form-control selectToo" id="distritosLocales" name="distritoLocal">
                                <option value="0">- - -</option>
                            </select>
                            @error('distritoLocal')
                                <div id="distritoLocalError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                    </div>
                </div>
                <br>
                <div class="p-4 border rounded-3 bg-secondary bg-opacity-10">
                    <h3>Datos de relación</h3>
                    <div class="row row-cols-1 row-cols-sm-3">
                        <div class="col">
                            <h4>Afiliado</h4>
                            <select class="form-control" name="esAfiliado">
                                <option select>No</option>
                                <option>Si</option>
                            </select>
                            @error('esAfiliado')
                                <div id="esAfiliadoError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
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
                                <div id="esSimpatizanteError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                        <div class="col">
                            <h4>Programas</h4>
                            <select class="form-control selectToo" name="programa">
                                <option select>Ninguno</option>
                                <option>Programa 1</option>
                                <option>Programa 2</option>
                            </select>
                            @error('programa')
                                <div id="programaError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                    </div>
                </div>
                <br>
                <div class="p-4 border rounded-3 bg-secondary bg-opacity-10">
                    <h3>Datos de estructura</h3>
                    <div class="row row-cols-1 row-cols-sm-3">
                        <div class="col">
                            <h4>Rol en estructura</h4>
                            <select class="form-control" id="rolEstructura" name="rolEstructura">
                                <option value="-1">Sin rol en la estructura</option>
                                <option value="COORDINADOR ESTATAL">COORDINADOR ESTATAL</option>
                                <option value="COORDINADOR DE DISTRITO LOCAL">COORDINADOR DE DISTRITO LOCAL</option>
                                <option value="COORDINADOR DE SECCIÓN">COORDINADOR DE SECCIÓN</option>
                                <option value="PROMOTOR">PROMOTOR</option>
                            </select>
                            @error('rolEstructura')
                                <div id="rolEstructuraError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                        <div class="col">
                            <h4 id="rolNumeroEncabezado">Seleccione un rol en estructura</h4>
                            <input type="number" class="form-control" id="rolNumero" name="rolNumero" value="{{old('rolNumero')}}" disabled>
                            @error('rolNumero')
                                <div id="rolNumeroError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                        <div class="col">
                            <h4>Función asignada</h4>
                            <select class="form-control selectToo" name="funciones">
                                <option select>Ninguno</option>
                                <option>Medicina</option>
                                <option>Lentes</option>
                            </select>
                            @error('funciones')
                                <div id="funcionAsignadaError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                    </div>
                </div>
                <br>
                <div class="p-4 border rounded-3 bg-secondary bg-opacity-10">
                    <h3>Otros datos</h3>
                    <h4>Etiquetas</h4>
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
                    <br>
                    <div class="row row-cols-1">
                        <div class="col">
                            <h4>Observaciones</h4>
                            <div class="form-group">
                                <textarea class="form-control" rows="5" id="comment" name="observaciones"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <small>(*) Son campos obligatorios para el formulario</small>
            </div>
            <br>
            <div>
                <center>
                    <button id="BotonAgregarPersona"  class="btn btn-primary" hidden></button>
                    <a id="BotonValidador" onclick="validar()" class="btn btn-primary" >Agregar Persona</a>
                    <!-- <button class="btn btn-danger" type="button" class="cerrarFormulario">Limpiar</button> -->
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

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDg60SDcmNRPnG1tzZNBBGFx02cW2VkWWQ&callback=initMap&v=weekly" defer></script>
<script src="/js/validacionesFormulario.js" text="text/javascript"></script>


<script text="text/javascript">
    function initMap() {
        var marker;
        var marker2;
        const myLatLng = { lat: 24.123954, lng: - 110.311664 };

        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 17,
            center: myLatLng,
            title: "Ubicación",
        });

        google.maps.event.addListener(map, 'click', function (event) {
            console.log(event.latLng);
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
        $.when(
            $.ajax({
                type: "get",
                url: "{{route('agregarSimpatizante.inicializar')}}",
                data: [],
                contentType: "application/x-www-form-urlencoded",
                success: function (response) {
                    $.each(response.colonias, function (i, valor) {
                        $('#colonias').append(
                            $('<option>').val(valor.id).text(valor.nombre)
                        );
                    });
                    $.each(response.municipios, function (i, valor) {
                        $('#municipios').append(
                            $('<option>').val(valor.id).text(valor.nombre)
                        );
                    });
                    $.each(response.secciones, function (i, valor) {
                        $('#secciones').append(
                            $('<option>').text(valor.id)
                        );
                    });
                    $.each(response.entidades, function (i, valor) {
                        $('#entidades').append(
                            $('<option>').val(valor.id).text(valor.nombre)
                        );
                    });
                    $.each(response.distritosFederales, function (i, valor) {
                        $('#distritosFederales').append(
                            $('<option>').text(valor.id)
                        );
                    });
                    $.each(response.distritosLocales, function (i, valor) {
                        $('#distritosLocales').append(
                            $('<option>').text(valor.id)
                        );
                    });
                    $.each(response.promotores, function (i, valor) {
                        $('#promotores').append(
                            $('<option>').val(valor.id).text(`${valor.nombres} ${valor.apellido_paterno}`)
                        );
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
        });
    });

    $('#rolEstructura').change(function (e) {
        $('#rolNumero').prop('disabled', false);
        switch ($(this).val()) {
            case 'COORDINADOR ESTATAL':
                $('#rolNumeroEncabezado').text('¿En qué Entidad?');
                break;
                case 'COORDINADOR DE DISTRITO LOCAL':
                $('#rolNumeroEncabezado').text('¿En qué Distrito?');
                break;
                case 'COORDINADOR DE SECCIÓN':
                $('#rolNumeroEncabezado').text('¿En qué Sección?');
                break;
                case 'PROMOTOR':
                $('#rolNumeroEncabezado').text('¿En qué Sección?');
                break;
            default:
                $('#rolNumeroEncabezado').text('Seleccione un rol en estructura');
                $('#rolNumero').prop('disabled', true);
                break;
        }
    });

    // FUNCION CERRAR FORMULARIO
    $('.cerrarFormulario').click(function (e) {
        $('#formularioAgregarSimpatizante')[0].reset();
    });

    $('#curp').on('input', soloMayusculas);
    $('#claveElectoral').on('input', soloMayusculas);
    function soloMayusculas(){
        $(this).val($(this).val().toUpperCase());
    }

    const button = document.querySelector('#agregarEtiquetaCrear');
    const tagInput = document.querySelector('#inputEtiquetaCrear');

    const tagContainer = document.querySelector('.contenedorEtiquetasCrear');
    let tags = [];

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
    $('#formularioAgregarSimpatizante').submit(function (e) {
        if($('#formularioAgregarSimpatizante #etiquetas').length == 0){
            let etiquetas = "";
            $.each(tags, function (i, value) {
                etiquetas += `${value.childNodes[0].innerHTML},`;
                if(etiquetas.length > 0 && tags.length - 1 == i){
                    etiquetas = etiquetas.slice(0, -1);
                }
            });
            $('#formularioAgregarSimpatizante').append(
                $('<input>').attr('name', 'etiquetas').attr('id', 'etiquetas').attr('type', 'hidden')
                .val(etiquetas)
            );
        }
    });

    function filtrarColonia(){
        let municipio = $('#municipios').val();
        let codigoPostal = ($('#codigoPostal').val() != '') ? $('#codigoPostal').val() : 0;
        let colonia = $('#colonias').val();
        console.log(municipio, codigoPostal, colonia);
        $.when(
        $.ajax({
                type: "get",
                url: `{{url('/')}}/simpatizantes/filtrarColonias-${municipio}-${codigoPostal}-${colonia}`,
                data: [],
                contentType: "application/x-www-form-urlencoded",
                success: function (response) {
                    console.log(response);
                    $('#municipios').html('');
                    $('#colonias').html('');

                    $('#municipios').append(
                        $('<option>').val(0).text('- - -')
                    );
                    $('#colonias').append(
                        $('<option>').val(0).text('- - -')
                    );
                    $.each(response[0].municipios, function (i, valor) {
                        if(response[1].municipio > 0 && response[1].municipio == valor.id){
                            $('#municipios').append(
                                $('<option>').val(valor.id).prop('selected', true).text(valor.nombre)
                            );
                        }
                        else{
                            $('#municipios').append(
                                $('<option>').val(valor.id).text(valor.nombre)
                            );
                        }
                    });

                    $.each(response[0].colonias, function (i, valor) {
                        if(response[1].colonia > 0 && response[1].colonia == valor.id){
                            $('#colonias').append(
                                $('<option>').val(valor.id).prop('selected', true).text(valor.nombre)
                            );
                        }
                        else{
                            $('#colonias').append(
                                $('<option>').val(valor.id).text(valor.nombre)
                            );
                        }
                    });

                    // $.each(response.colonias, function (i, valor) {
                    //     $('#colonias').append(
                    //         $('<option>').val(valor.id).text(valor.nombre)
                    //     );
                    // });
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
    $('#municipios').change(filtrarColonia);
    $('#colonias').change(filtrarColonia);
    $('#codigoPostal').keyup(function (e) {
        console.log($('#codigoPostal').val());
        if($('#codigoPostal').val().length == 5){
            filtrarColonia();
        }
    });
    function filtrarSecciones(){
        let entidad = $('#entidades').val();
        let distritoFederal = $('#distritosFederales').val();
        let distritoLocal = $('#distritosLocales').val();
        let seccion = $('#secciones').val();
        $.when(
        $.ajax({
                type: "get",
                url: `{{url('/')}}/simpatizantes/filtrarSecciones-${entidad}-${distritoFederal}-${distritoLocal}-${seccion}`,
                data: [],
                contentType: "application/x-www-form-urlencoded",
                success: function (response) {
                    $('#entidades').html('');
                    $('#distritosFederales').html('');
                    $('#distritosLocales').html('');
                    $('#secciones').html('');

                    $('#entidades').append(
                        $('<option>').val(0).text('- - -')
                    );
                    $('#distritosFederales').append(
                        $('<option>').val(0).text('- - -')
                    );
                    $('#distritosLocales').append(
                        $('<option>').val(0).text('- - -')
                    );
                    $('#secciones').append(
                        $('<option>').val(0).text('- - -')
                    );

                    $.each(response[0].entidades, function (i, valor) {
                        if(response[1].entidad > 0 && response[1].entidad == valor.id){
                            $('#entidades').append(
                                $('<option>').val(valor.id).prop('selected', true).text(valor.nombre)
                            );
                        }
                        else{
                            $('#entidades').append(
                                $('<option>').val(valor.id).text(valor.nombre)
                            );
                        }
                    });

                    $.each(response[0].distritosFederales, function (i, valor) {
                        if(response[1].distritoFederal > 0 && response[1].distritoFederal == valor.id){
                            $('#distritosFederales').append(
                                $('<option>').val(valor.id).prop('selected', true).text(valor.id)
                            );
                        }
                        else{
                            $('#distritosFederales').append(
                                $('<option>').val(valor.id).text(valor.id)
                            );
                        }
                    });

                    $.each(response[0].distritosLocales, function (i, valor) {
                        if(response[1].distritoLocal > 0 && response[1].distritoLocal == valor.id){
                            $('#distritosLocales').append(
                                $('<option>').val(valor.id).prop('selected', true).text(valor.id)
                            );
                        }
                        else{
                            $('#distritosLocales').append(
                                $('<option>').val(valor.id).text(valor.id)
                            );
                        }
                    });

                    $.each(response[0].secciones, function (i, valor) {
                        if(response[1].seccion > 0 && response[1].seccion == valor.id){
                            $('#secciones').append(
                                $('<option>').val(valor.id).prop('selected', true).text(valor.id)
                            );
                        }
                        else{
                            $('#secciones').append(
                                $('<option>').val(valor.id).text(valor.id)
                            );
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
        });
    }

    $('#entidades').change(filtrarSecciones);
    $('#distritosFederales').change(filtrarSecciones);
    $('#distritosLocales').change(filtrarSecciones);
    $('#secciones').change(filtrarSecciones);
    </script>
@endsection

