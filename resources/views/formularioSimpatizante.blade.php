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
                            <input type="date" class="form-control" id="fechaRegistro" name="fechaRegistro" min="{{date('Y-m-d', strtotime('-100 years'))}}" max="{{date('Y-m-d')}}" value="{{date('Y-m-d')}}">
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
                            <select class="form-select selectToo" id="promotores" name="promotor">
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
                            <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" value="{{old('apellido_paterno')}}"
                            minlength="3" maxlength="255" onkeydown="return /[a-z, ]/i.test(event.key)"
                            onblur="if (this.value == '') {this.value = '';}" onfocus="if (this.value == '') {this.value = '';}">
                            @error('apellido_paterno')
                                <div id="apellidoPaternoError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                        <div class="col">
                            <h4>Apellido materno (*)</h4>
                            <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" value="{{old('apellido_materno')}}"
                            minlength="3" maxlength="255" onkeydown="return /[a-z, ]/i.test(event.key)"
                            onblur="if (this.value == '') {this.value = '';}" onfocus="if (this.value == '') {this.value = '';}">
                            @error('apellido_materno')
                                <div id="apellidoMaternoError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                        <div class="col">
                            <h4>Nombre(s) (*)</h4>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="{{old('nombre')}}" minlength="3" maxlength="255"
                            onkeydown="return /[a-z, ]/i.test(event.key)"
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
                            <select name="genero" class="form-control">
                                <option {{old('genero') == 'SIN ESPECIFICAR' ? 'selected' : ''}} value="SIN ESPECIFICAR">SIN ESPECIFICAR</option>
                                <option {{old('genero') == 'HOMBRE' ? 'selected' : ''}} value="HOMBRE">HOMBRE</option>
                                <option {{old('genero') == 'MUJER' ? 'selected' : ''}} value="MUJER">MUJER</option>
                            </select>
                            @error('genero')
                                <div id="generoError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                        <div class="col">
                            <h4>Fecha de Nacimiento</h4>
                            <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento" value="{{old('fechaNacimiento')}}"
                            min="{{date('Y-m-d', strtotime('-100 years'))}}" max="{{date('Y-m-d', strtotime('-18 years'))}}">
                            @error('fechaNacimiento')
                                <div id="fechaNacimientoError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                        <div class="col">
                            <h4>Rango de edad</h4>
                            <select id="rangoEdad" class="form-select" name="rangoEdad">
                                <option {{old('rangoEdad') == '23' ? 'selected' : ''}} value="23">18-28</option>
                                <option {{old('rangoEdad') == '34' ? 'selected' : ''}} value="34">29-39</option>
                                <option {{old('rangoEdad') == '45' ? 'selected' : ''}} value="45">40-49</option>
                                <option {{old('rangoEdad') == '55' ? 'selected' : ''}} value="55">50-69</option>
                                <option {{old('rangoEdad') == '74' ? 'selected' : ''}} value="74">70-adelante</option>
                            </select>
                            @error('rangoEdad')
                                <div id="rangoEdadError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                    </div>
                    <div class="row row-cols-1 row-cols-sm-3">
                        <div class="col">
                            <h4>Escolaridad</h4>
                            <select class="form-select" name="escolaridad">
                                <option {{old('escolaridad') == 'SIN ESTUDIOS' ? 'selected' : ''}}>SIN ESTUDIOS</option>
                                <option {{old('escolaridad') == 'PRIMARIA' ? 'selected' : ''}}>PRIMARIA</option>
                                <option {{old('escolaridad') == 'SECUNDARIA' ? 'selected' : ''}}>SECUNDARIA</option>
                                <option {{old('escolaridad') == 'PREPARATORIA' ? 'selected' : ''}}>PREPARATORIA</option>
                                <option {{old('escolaridad') == 'UNIVERSIDAD' ? 'selected' : ''}}>UNIVERSIDAD</option>
                                <option {{old('escolaridad') == 'MAESTRIA' ? 'selected' : ''}}>MAESTRIA</option>
                                <option {{old('escolaridad') == 'DOCTORADO' ? 'selected' : ''}}>DOCTORADO</option>
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
                            <input type="number" class="form-control" id="telefonoCelular" name="telefonoCelular" value="{{old('telefonoCelular')}}"
                            minlength="10" maxlength="12">
                            @error('telefonoCelular')
                                <div id="telefonoCelularError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                        <div class="col">
                            <h4>Telefono Fijo</h4>
                            <input type="number" class="form-control" id="telefonoFijo" name="telefonoFijo" value="{{old('telefonoFijo')}}"
                            minlength="10" maxlength="12">
                            @error('telefonoFijo')
                                <div id="telefonoFijoError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                        <div class="col">
                            <h4>Correo electronico (*)</h4>
                            <input type="email" style="text-transform: uppercase; color: black"  class="form-control" id="correo" name="correo"
                            value="{{old('correo')}}" minlength="3" maxlength="255">
                            @error('correo')
                                <div id="correoError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                    </div>
                    <br>
                    <div class="row row-cols-1 row-cols-sm-3">
                        <div class="col">
                            <h4>Facebook</h4>
                            <input type="text" class="form-control" id="facebook" name="facebook" value="{{old('facebook')}}" minlength="3"
                            maxlength="255">
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
                            <input type="text" class="form-control" id="calle" name="calle" value="{{old('calle')}}"
                            onkeydown="return /[a-z, ]/i.test(event.key)"
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
                            <h4>Colonia (*)</h4>
                            <select class="form-select selectToo" id="colonias" name="colonia">
                                <option value="0">Seleccione una colonia</option>
                            </select>
                            @error('colonia')
                                <div id="coloniaError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                        <div class="col">
                            <h4>Código Postal (*)</h4>
                            <input type="number" class="form-control" id="codigoPostal" name="codigoPostal" value="{{old('codigoPostal')}}">
                            @error('codigoPostal')
                            <div id="codigoPostalError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                        <div class="col">
                            <h4>Municipio o Delegación (*)</h4>
                            <select class="form-select selectToo" id="municipios" name="municipio">
                                <option value="0">Seleccione un municipio</option>
                            </select>
                            @error('municipio')
                                <div id="municipioError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>


                    </div>
                    <br>
                    <h4>¿Donde vive la persona?</h4>
                    <center>
                        <input type="hidden" id="coordenadas" name="coordenadas" value="{{old('coordenadas')}}">
                        <input type="text" class="col-3 d-none" id="cordenada" class="form-control" value="{{old('coordenadas')}}" disabled>
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
                            <input type="text" style="text-transform: uppercase; color: black" class="form-control" id="claveElectoral" name="claveElectoral" value="{{old('claveElectoral')}}"
                            minlength="18" maxlength="18" placeholder="ABCDEF12345678B123">
                            @error('claveElectoral')
                                <div id="claveElectoralError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                        <div class="col">
                            <h4>CURP</h4>
                            <input type="text" style="text-transform: uppercase; color: black" class="form-control" id="curp" name="curp" value="{{old('curp')}}" minlength="18" maxlength="18"
                            placeholder="ABCD123456HBCDEF12">
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
                            <select class="form-select selectToo" id="secciones" name="seccion">
                                <option value="0">Seleccione una sección</option>
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
                            <select class="form-select selectToo" id="entidades" name="entidadFederativa">
                                <option value="0">Seleccione una entidad federativa</option>
                            </select>
                            @error('entidadFederativa')
                                <div id="entidadFederativaError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                        <div class="col">
                            <h4>Distrito Federal</h4>
                            <select class="form-select selectToo" id="distritosFederales" name="distritoFederal">
                                <option value="0">Seleccione un distrito federal</option>
                            </select>
                            @error('distritoFederal')
                                <div id="distritoFederalError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                        <div class="col">
                            <h4>Distrito local</h4>
                            <select class="form-select selectToo" id="distritosLocales" name="distritoLocal">
                                <option value="0">Seleccione un distrito local</option>
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
                            <select class="form-select" name="esAfiliado">
                                <option {{old('esAfiliado') == 'No' ? 'selected' : ''}}>No</option>
                                <option {{old('esAfiliado') == 'Si' ? 'selected' : ''}}>Si</option>
                            </select>
                            @error('esAfiliado')
                                <div id="esAfiliadoError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                        <div class="col">
                            <h4>Simpatizantes</h4>
                            <select class="form-select" name="esSimpatizante">
                                <option {{old('esSimpatizante') == 'No' ? 'selected' : ''}}>No</option>
                                <option {{old('esSimpatizante') == 'Si' ? 'selected' : ''}}>Si</option>
                                <option {{old('esSimpatizante') == 'Talvez' ? 'selected' : ''}}>Talvez</option>
                            </select>
                            @error('esSimpatizante')
                                <div id="esSimpatizanteError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                        <div class="col">
                            <h4>Programas</h4>
                            <select class="form-select selectToo" name="programa">
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
                            <select class="form-select" id="rolEstructura" name="rolEstructura">
                                <option {{old('rolEstructura') == '-1' ? 'selected' : ''}} value="-1">Sin rol en la estructura</option>
                                <option {{old('rolEstructura') == 'COORDINADOR ESTATAL' ? 'selected' : ''}} value="COORDINADOR ESTATAL">COORDINADOR ESTATAL</option>
                                <option {{old('rolEstructura') == 'COORDINADOR DE DISTRITO LOCAL' ? 'selected' : ''}} value="COORDINADOR DE DISTRITO LOCAL">COORDINADOR DE DISTRITO LOCAL</option>
                                <option {{old('rolEstructura') == 'COORDINADOR DE SECCIÓN' ? 'selected' : ''}} value="COORDINADOR DE SECCIÓN">COORDINADOR DE SECCIÓN</option>
                                <option {{old('rolEstructura') == 'PROMOTOR' ? 'selected' : ''}} value="PROMOTOR">PROMOTOR</option>
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
                            <input type="text" class="form-control" id="funciones" name="funciones" value="{{old('funciones')}}">
                            @error('funciones')
                                <div id="funcionAsignadaError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                    </div>
                    <div class="row row-cols-1 row-cols-sm-3">
                        <div class="col">
                            <h4>¿Tiene un rol temporal?</h4>
                            <select class="form-select" id="tieneRolTemporal" name="tieneRolTemporal">
                                <option {{old('tieneRolTemporal') == 'NO' ? 'selected' : ''}} value="NO">No</option>
                                <option {{old('tieneRolTemporal') == 'SI' ? 'selected' : ''}} value="SI">Si</option>
                            </select>
                        </div>
                        <div class="col">
                            <h4>Rol en estructura temporal</h4>
                            <select class="form-select" id="rolEstructuraTemporal" name="rolEstructuraTemporal" disabled>
                                <option {{old('rolEstructuraTemporal') == '-1' ? 'selected' : ''}} value="-1">Sin rol en la estructura</option>
                                <option {{old('rolEstructuraTemporal') == 'COORDINADOR ESTATAL' ? 'selected' : ''}} value="COORDINADOR ESTATAL">COORDINADOR ESTATAL</option>
                                <option {{old('rolEstructuraTemporal') == 'COORDINADOR DE DISTRITO LOCAL' ? 'selected' : ''}} value="COORDINADOR DE DISTRITO LOCAL">COORDINADOR DE DISTRITO LOCAL</option>
                                <option {{old('rolEstructuraTemporal') == 'COORDINADOR DE SECCIÓN' ? 'selected' : ''}} value="COORDINADOR DE SECCIÓN">COORDINADOR DE SECCIÓN</option>
                                <option {{old('rolEstructuraTemporal') == 'PROMOTOR' ? 'selected' : ''}} value="PROMOTOR">PROMOTOR</option>
                            </select>
                            @error('rolEstructuraTemporal')
                                <div id="rolEstructuraTemporalError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
                            @enderror
                        </div>
                        <div class="col">
                            <h4 id="rolNumeroEncabezadoTemporal">Seleccione un rol en estructura</h4>
                            <input type="number" class="form-control" id="rolNumeroTemporal" name="rolNumeroTemporal" value="{{old('rolNumeroTemporal')}}" disabled>
                            @error('rolNumeroTemporal')
                                <div id="rolNumeroTemporalError" class="p-2 mt-2 rounded-3 bg-danger text-white"><small>{{$message}}</small></div>
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
                </center>
            </div>
        </div>
    </form>
</div>


    </script>
@endsection

@section('scripts')
@if (session()->has('validarCamposFormPersona'))
    <script>
        Swal.fire({
            'tittle':"Error",
            'text':"{{session('validarCamposFormPersona')}}",
            'icon':"error"
        });
    </script>
@endif
  {{-- PASAR LIBRERIAS A PLANTILLA --}}
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
        // service.findPlaceFromQuery(request, (results, status) => {
        //     if (status === google.maps.places.PlacesServiceStatus.OK && results) {
        //     for (let i = 0; i < results.length; i++) {
        //         createMarker(results[i]);
        //     }
        //         map.setCenter(results[0].geometry.location);
        //     }
        // });

        google.maps.event.addListener(map, 'click', function (event) {
            console.log(event.latLng);
            placeMarker(event.latLng);
            document.getElementById("cordenada").value = event.latLng.lat() + ", " + event.latLng.lng();
            document.getElementById("coordenadas").value = event.latLng.lat() + "," + event.latLng.lng();
        });
        @if(old('coordenadas'))
            placeMarker({lat: {{explode(',', old('coordenadas'))[0]}}, lng: {{explode(',', old('coordenadas'))[1]}}})
        @endif

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
    function buscarUbicacion(nombre) {
        // Clave de API de Google Maps (reemplaza 'TU_API_KEY' con tu propia clave)
        var apiKey = 'AIzaSyDg60SDcmNRPnG1tzZNBBGFx02cW2VkWWQ';

        // URL de la API de Geocodificación de Google Maps
        var url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' + encodeURIComponent(nombre) + '&key=' + apiKey;

        // Realizar la solicitud HTTP GET utilizando Fetch API
        fetch(url)
        .then(response => response.json())
        .then(data => {
            // Verificar si la respuesta tiene resultados
            if (data.results.length > 0) {
                // Obtener las coordenadas de la primera ubicación encontrada
                var ubicacion = data.results[0].geometry.location;
                var latitud = ubicacion.lat;
                var longitud = ubicacion.lng;

                // Aquí puedes usar latitud y longitud como desees
                console.log('Latitud:', latitud);
                console.log('Longitud:', longitud);
                placeMarker({lat: latitud, lng: longitud});
            } else {
                console.log('No se encontraron resultados para la ubicación:', nombre);
            }
        })
        .catch(error => {
            console.error('Error al buscar la ubicación:', error);
        });
    }


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

                    @if(old('colonia'))
                        $('#colonias').val({{old('colonia')}});
                    @endif
                    @if(old('municipio'))
                        $('#municipios').val({{old('municipio')}});
                    @endif
                    @if(old('seccion'))
                        $('#secciones').val({{old('seccion')}});
                    @endif
                    @if(old('entidadFederativa'))
                        $('#entidades').val({{old('entidadFederativa')}});
                    @endif
                    @if(old('distritoFederal'))
                        $('#distritosFederales').val({{old('distritoFederal')}});
                    @endif
                    @if(old('distritoLocal'))
                        $('#distritosLocales').val({{old('distritoLocal')}});
                    @endif
                    @if(old('promotor'))
                        $('#promotores').val({{old('promotor')}});
                    @endif
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
        @if (old('fechaRegistro'))
            $('#fechaRegistro').val("{{old('fechaRegistro')}}");
        @endif
        @if (old('etiquetas'))
            let etiquetasCrudas = @json(old('etiquetas'));
            let etiquedasPreprocesar = (etiquetasCrudas != null) ? etiquetasCrudas.split(',') : [];
            $.each(etiquedasPreprocesar, function (i, valor) {
                createTag(valor);
            });
        @endif
        @if (old('rolEstructura'))
            $('#rolEstructura').trigger("change");
        @endif
        @if (old('rolEstructuraTemporal'))
            $('#tieneRolTemporal').trigger('change');
            $('#rolEstructuraTemporal').trigger("change");
        @endif

    });


    $('#rolEstructuraTemporal').change(function (e) {
        $('#rolNumeroTemporal').prop('disabled', false);
        switch ($(this).val()) {
            case 'COORDINADOR ESTATAL':
                $('#rolNumeroEncabezadoTemporal').text('¿En qué Entidad?');
                break;
                case 'COORDINADOR DE DISTRITO LOCAL':
                $('#rolNumeroEncabezadoTemporal').text('¿En qué Distrito?');
                break;
                case 'COORDINADOR DE SECCIÓN':
                $('#rolNumeroEncabezadoTemporal').text('¿En qué Sección?');
                break;
                case 'PROMOTOR':
                $('#rolNumeroEncabezadoTemporal').text('¿En qué Sección?');
                break;
            default:
                $('#rolNumeroEncabezadoTemporal').text('Seleccione un rol en estructura');
                $('#rolNumeroTemporal').prop('disabled', true);
                break;
        }
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
        if (key === ',' || key === 'Enter') {
            e.preventDefault();
            createTag(tagInput.value.substring(0, tagInput.value.length - 1));
        }
    });
    button.addEventListener('click', (e) => {
        createTag(tagInput.value);
    });
    $('#formularioAgregarSimpatizante').submit(function (e) {
        if($('#inputEtiquetaCrear').is(':focus')){
            return false;
        }
        else{
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
        }
    });

    function filtrarColonia(){
        let colonia = $('#colonias').val();
        $.when(
        $.ajax({
                type: "get",
                url: `{{url('/')}}/simpatizantes/filtrarColonias-${colonia}`,
                data: [],
                contentType: "application/x-www-form-urlencoded",
                success: function (response) {
                    console.log(response);
                    $('#municipios').val(response.municipio);
                    $('#municipios').trigger('change');
                    $('#codigoPostal').val(response.codigoPostal);
                    // buscarUbicacion(`${colonia}, ${response.nombreColonia}, ${response.nombreMunicipio}`);
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
    $('#colonias').change(filtrarColonia);

    function filtrarSecciones(){
        let seccion = $('#secciones').val();
        $.when(
        $.ajax({
                type: "get",
                url: `{{url('/')}}/simpatizantes/filtrarSecciones-${seccion}`,
                data: [],
                contentType: "application/x-www-form-urlencoded",
                success: function (response) {
                    console.log(response);
                    $('#entidades').val(response.entidad);
                    $('#entidades').trigger('change');
                    $('#distritosFederales').val(response.distritoFederal);
                    $('#distritosFederales').trigger('change');
                    $('#distritosLocales').val(response.distritoLocal);
                    $('#distritosLocales').trigger('change');
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
    $('#secciones').change(filtrarSecciones);

    $('#fechaNacimiento').change(function (e) {
        var fechaNacimiento = new Date($('#fechaNacimiento').val());
        var hoy = new Date();

        var edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
        var mes = hoy.getMonth() - fechaNacimiento.getMonth();
        if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNacimiento.getDate())) {
            edad--;
        }
        if(18 <= edad && edad <= 28){
            $('#rangoEdad').val(23);
        }
        else if(29 <= edad && edad <= 39){
            $('#rangoEdad').val(34);
        }
        else if(40 <= edad && edad <= 49){
            $('#rangoEdad').val(45);
        }
        else if(50 <= edad && edad <= 69){
            $('#rangoEdad').val(55);
        }
        else if(70 <= edad){
            $('#rangoEdad').val(74);
        }
    });

    $('#tieneRolTemporal').change(function (e) {
        let opcion = $('#tieneRolTemporal').val();
        if(opcion == 'SI'){
            $('#rolEstructuraTemporal').prop('disabled', false);
            $('#rolNumeroTemporal').prop('disabled', false);
            $('#rolEstructuraTemporal').trigger('change');
        }
        else{
            $('#rolEstructuraTemporal').prop('disabled', true);
            $('#rolNumeroTemporal').prop('disabled', true);
        }
    });
    </script>
@endsection

