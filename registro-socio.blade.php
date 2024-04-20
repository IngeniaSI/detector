<div>
    <main>
        <form id="formRegistroSocio" action="{{route('panelSocio.registroSocio.registrar')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <section class="d-block col-md-9 col-11 mb-4 mx-auto">
                <h3 id="textoActividad" class="text-center mb-3 fw-bold">{{__('Activity information')}}</h3>
                <div class="d-sm-flex justify-content-between col-6 mx-auto mb-3 celular">
                    <input type="text" wire:model="nombreActividad" maxlength="254" name="nombreActividad" id="nombreActividad" placeholder="Nombre de la actividad" class="col-sm-5 col-12">

                    <div class="custom-select col-md-5 col-12">
                        <select wire:model="filtroEstadoTour" id="filtroEstadoTour" name="filtroEstadoTour" class="cmbBox inputDatosGenerales txt" >
                            <option id="opcion0EstadoTour" class="optionCombobox " value="0">Estado de la actividad</option>
                            @foreach ($estados as $es)
                                <option class="optionCombobox" value="{{$es->id}}">{{$es->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="d-sm-flex justify-content-between col-6 mx-auto mb-3  celular">
                    <div class="custom-select col-md-5 col-12">
                        <select class="cmbBox" id="categoriaActividad" name="categoriaActividad" wire:model="categoriaActividad">
                            <option id="opcion0CategoriaTour" selected class="optionCombobox " value="0">Categoría de la actividad </option>
                            @foreach ($categoria as $cat)
                                <option class="optionCombobox" value="{{$cat}}">{{$cat}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="custom-select col-md-5 col-12">
                        <select class="cmbBox" name="ciudadActividad" id="ciudadActividad" wire:model="ciudadActividad">
                            <option id="opcion0CiudadTour" class="optionCombobox" value="">Ciudad de la actividad</option>
                                @foreach ($ciudadesTour as $ciu)
                                    <option class="optionCombobox" value="{{$ciu->id}}">{{$ciu->nombre}}</option>
                                @endforeach
                        </select>
                    </div>
                </div>

                <div class="d-sm-flex justify-content-between col-6 mx-auto mb-3 celular">
                    <div class="d-block col-md-5 col-12">
                        <p id="textoNumPersonas" class="col-md-12 col-11 ms-md-2"> Número de personas</p>
                        <div class="d-flex justify-content-between mt-2">
                            <input type="text" wire:model="minPersona" maxlength="9" name="minPersona" id="minPersona" placeholder="Mínimo" class="col-md-5 col-6">
                            <input type="text" wire:model="maxPersona" maxlegth="9" name="maxPersona" id="maxPersona" placeholder="Máximo" class="col-md-5 col-6 ms-md-4">
                        </div>
                    </div>
                    <div class="d-block col-md-5 pt-3 ps-md-2 col-12">
                        <p wire:key='idiomasTexto0' id="idiomasTexto" >¿Tu(s) Guías hablan inglés y español?</p>
                        <div class="d-flex justify-content-between col-4 mt-2">
                            <p id="idiomaSi">Sí</p>
                            <input wire:model="guiasIngles" type="radio" class="col-6" name="guiasIngles" id="guiasIngles" checked value="Ambos">
                            <p id="idiomaNo">No</p>
                            <input wire:model="guiasIngles" type="radio" class="col-6" name="guiasIngles" id="guiasIngles" value="Español">
                        </div>
                    </div>
                </div>
            </section>

            <section class="d-block col-md-9 col-11 mx-auto">
                <h3 id="textoAnfitrion" class="text-center pt-3 mb-3 fw-bold"> Información del Anfitrión</h3>
                <div class=" col-sm-8 col-11 mx-auto mb-5 ps-2 ">
                    <i class="fa fa-exclamation" style="padding-left: 10px;"> </i>
                    <span id="textoPrivacidad">Utilizaremos esta información para dar de alta tu cuenta y mantenerte seguro. No aparecera en la información del tour.</span>
                </div>

                <div class="d-md-block col-md-8 mx-md-auto mb-md-5">
                    <p id="textoDatos" style="margin-left:2%;" class="fw-bold fs-5 ps-2">Datos Generales</p>
                    <div class="d-md-flex justify-content-md-around col-md-4 mx-auto mb-md-3 d-sm-block mar">
                        <input wire:model="nombres" type="text" maxlength="100" name="nombres" id="nombres" placeholder="Nombre(s)" class="col-md-10  col-11  textbox">
                        <input wire:model="apellidos" type="text" maxlength="100" name="apellidos" id="apellidos" placeholder="Apellidos" class="col-md-10 ms-md-5  col-11 textbox">
                        <input wire:model="correo" type="text" maxlength="254" name="correo" id="correo" placeholder="Correo electrónico" class="col-md-10 ms-md-5 col-11  textbox">
                    </div>
                    <div class="d-md-flex justify-content-md-around col-md-4 mx-auto mb-3 d-sm-block mar">
                        <input wire:model="telefono" type="text" maxlength="17" name="telefono" id="telefono" placeholder="Telefono de contacto" class="col-md-10 col-11 textbox">
                        <input wire:model="password" type="password" name="password" id="password" placeholder="Contraseña" class="col-md-10 ms-md-5 col-11 textbox">
                        <input wire:model="passwordAgain" type="password" name="passwordAgain" id="passwordAgain" placeholder="Repetir contraseña" class="col-md-10 ms-md-5 col-11 textbox">
                    </div>
                    <div class="d-md-flex justify-content-md-around col-md-4 mx-auto mb-3 d-sm-block mar">

                        <div class="col-md-10 col-11">
                            <select class="cmbBox" id="tipoOperador" name="tipoOperador" wire:model="tipoOperador">
                                <option selected id="opcion0TipoOperador" class="optionCombobox " value="0">Tipo de Operador</option>
                                <option id="opcion1TipoOperador" class="optionCombobox" value="Operador final">Operador Final</option>
                                <option id="opcion2TipoOperador" class="optionCombobox" value="Operador intermediario">Operador Intermediario</option>
                            </select>
                        </div>
                        <div class="custom-select col-md-10 ms-md-5">
                        </div>
                        <div class="custom-select col-md-10 ms-md-5">
                        </div>
                    </div>
                </div>

                <div class="d-md-block col-md-8 mx-md-auto mb-md-5">
                    <p id="textoFiscales" style="margin-left:2%;" class="fw-bold fs-5 ps-2">Datos fiscales</p>
                    <div class="d-md-flex justify-content-md-around col-md-4 mx-auto mb-3 mar">
                        <input wire:model="rfc" type="text" maxlength="13" name="rfc" id="rfc" placeholder="RFC" class="col-md-10 col-11 textbox">

                        <div class="d-block col-md-10 ms-md-5">
                            <p id="textoFisicaMoral">¿Eres una persona física o moral?</p>
                            <div class="d-flex justify-content-between col-4 mt-1">
                                <p id="textoFisica">Física</p>
                                <input wire:model="tipoPersona" type="radio" class="col-6" name="tipoPersona" wire:model="tipoPersona" id="tipoPersona" value="Fisica" checked>
                                <p id="textoMoral">Moral</p>
                                <input wire:model="tipoPersona" type="radio" class="col-6" name="tipoPersona" wire:model="tipoPersona" id="tipoPersona" value="Moral">
                            </div>
                        </div>

                        <div class="col-md-10 ms-md-5">
                            <div class="d-block col-md-12 ms-md-2">
                                <p id="preguntaSoftware">¿Utilizas algún software de gestión?</p>
                                <div class="d-flex justify-content-between col-4 mt-1">
                                    <p id="softwareSi">Sí</p>
                                    <input wire:model="softwareTercero" type="radio" class="col-6" wire:model="softwareTercero" name="softwareTercero" value="Si" id="softwareTercero">
                                    <p id="softwareNo" class="ps-4">No</p>
                                    <input wire:model="softwareTercero" type="radio" class="col-6" wire:model="softwareTercero" name="softwareTercero" value="No" checked id="softwareTercero">
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- AAAA --}}
                    @if ($tipoPersona == 'Moral')
                        <div class="d-md-flex justify-content-around col-md-4 mx-md-auto mb-md-3 mar">
                            <input wire:model="razonSocial" type="text" maxlength="254" name="razonSocial" id="razonSocial" placeholder="Razón Social" class="col-md-10 textbox">
                            <input wire:model="nombreComercial" type="text" maxlength="254" name="nombreComercial" id="nombreComercial" placeholder="Nombre Comercial" class="col-md-10 ms-md-5 textbox">
                            <div class="col-md-10 ms-md-5">
                            </div>
                        </div>
                    @endif
                </div>
                {{-- AAAA --}}
                @if ($softwareTercero == 'Si')
                    <div class="d-md-block col-md-8 mx-md-auto mb-md-5">
                        <p id="textoProgramasGestionEncabezado" style="margin-left:2%;" class="fw-bold fs-5 ps-2">Programas de gestión</p>
                        <div class="d-md-flex justify-content-md-around col-md-4 mx-auto mb-3 mar">
                            <input wire:model="nombreSoftware" type="text" maxlength="254" name="nombreSoftware" id="nombreSoftware" placeholder="¿Cuál?" class="col-md-10 textbox">
                            <div class="custom-select col-md-10 ms-md-5">
                            </div>
                            <div class="d-block col-md-10 ms-md-5">
                            </div>
                        </div>
                    </div>
                @endif
                <div class="d-md-block col-md-8 mx-md-auto mb-md-5">
                    <p id="textoDireccionFiscal" style="margin-left:2%;" class="fw-bold fs-5 ps-2">Dirección fiscal</p>

                    <div class="d-md-flex justify-content-around col-md-4 mx-md-auto mb-md-3 mar ">
                        <input wire:model="calle" type="text" maxlength="254" name="calle" id="calle" placeholder="Calle(s)" class="col-md-10  col-11  textbox">
                        <input wire:model="noExterior" type="text" maxlength="5" name="noExterior" id="noExterior" placeholder="Número de exterior" class="col-md-10 ms-md-5 col-11  textbox">
                        <input wire:model="colonia" type="text" maxlength="254" name="colonia" id="colonia" placeholder="Colonia" class="col-md-10 ms-md-5  col-11 textbox">
                    </div>
                    <div class="d-flex flex-column-reverse flex-sm-row justify-content-md-around col-md-4 mx-auto mb-md-3 mar">
                        <input wire:model="codigoPostal" type="text" maxlength="5" name="codigoPostal" id="codigoPostal" placeholder="Código Postal" class="col-md-10 col-11 textbox">
                        <div class="custom-select col-md-10 ms-md-5 col-11">
                            <select class="cmbBox" id="estadoDireccion" name="estadoDireccion" wire:model="estadoDireccion">
                                <option id="opcion0TourOrigen" value="0">Estado</option>
                                @foreach ($estados as $es)
                                    <option class="optionCombobox" value="{{$es->id}}">{{$es->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="custom-select col-md-10 ms-md-5 col-11">
                            <select class="cmbBox" id="ciudadDireccion" name="ciudadDireccion" wire:model="ciudadDireccion">
                                <option id="opcion0CiudadOrigen" class="optionCombobox" value="">Ciudad</option>
                            @foreach ($ciudadesDireccion as $ciu)
                                <option class="optionCombobox" value="{{$ciu->id}}">{{$ciu->nombre}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="d-md-block col-md-8 mx-md-auto mb-md-5">
                    <p id="tituloAMAV" style="margin-left:2%;" class="fw-bold fs-5 ps-2 col-12">Asociaciones de turismo</p>

                    <div class="d-md-flex justify-content-around col-md-4 mx-md-auto mb-md-3 mar mt-md-2">
                        <div class="col-md-10 ms-md-3">
                            <div class="d-block col-md-12 me-md-5">
                                <p>¿Te encuentras registrado en el Registro Nacional de Turismo?</p>
                                <div class="d-flex justify-content-between col-4 mt-1 mb-3">
                                    <p id="">Si</p>
                                    <input wire:model="codigoRnt" type="radio" class="col-6" name="codigoRnt" wire:model="codigoRnt" id="codigoRnt" value="Si">
                                    <p id="">No</p>
                                    <input wire:model="codigoRnt" type="radio" class="col-6" name="codigoRnt" wire:model="codigoRnt" id="codigoRnt" value="No" checked>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-10 ms-md-5">
                            <div class="d-block col-md-12 me-md-5">
                                <p id="preguntaAMAV">¿Asociado a la Asociación Mexicana de Agencias de Viajes?</p>
                                <div class="d-flex justify-content-between col-4 mt-1 mb-3">
                                    <p id="AMAVSi">Si</p>
                                    <input wire:model="amav" type="radio" class="col-6" name="amav" wire:model="amav" id="amav" value="Si">
                                    <p id="AMAVNo">No</p>
                                    <input wire:model="amav" type="radio" class="col-6" name="amav" wire:model="amav" id="amav" value="No" checked>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-10 ms-md-5"></div>
                    </div>
                    <div class="d-md-flex justify-content-around col-md-4 mx-md-auto mb-md-3 mar mt-md-2">
                        <div class="col-md-10 ms-md-3">
                            {{-- aaaaaaaaa --}}
                            @if($codigoRnt == 'Si')
                                <input wire:model="numeroRnt" type="text" maxlength="254" name="numeroRnt" id="numeroRnt" placeholder="Número RNT" class="col-md-12 mt-md-3 textbox">
                            @endif
                        </div>
                        <div class="col-md-10 ms-md-5">
                            {{-- aaaaaaaaa --}}
                            @if($amav == 'Si')
                                <input wire:model="representanteAMAV" type="text" maxlength="254" name="representanteAMAV" id="representanteAMAV" placeholder="Representante AMAV" class="col-md-12 mt-md-3 textbox">
                            @endif
                        </div>
                        <div class="col-md-10 ms-md-5"></div>
                    </div>
                </div>

                <div id="contenedorDocumentos" class="d-md-block col-md-8 mx-md-auto mb-md-5 align-items-center">
                    <h3 id="encabezadoDocumentacion" class="text-center fw-bold">Documentación</h3>
                    <input type="file" accept="image/*,.pdf" wire:model="fotosID" name="fotosID" id="fotosID" class="d-none">
                    <input type="file" accept="image/*,.pdf" wire:model="polizaSeguros" name="polizaSeguros" id="polizaSeguros" class="d-none">
                    <input type="file" accept="image/*,.pdf" wire:model="comprobanteDireccionFiscal" name="comprobanteDireccionFiscal" id="comprobanteDireccionFiscal" class="d-none">
                    <input type="file" accept="image/*,.pdf" wire:model="cif" name="cif" id="cif" class="d-none">
                    <div class="d-md-flex justify-content-between col-12 mb-1">
                        <div id="contenedor1" class="align-self-center col-sm-3 text-center position-relative" wire:key='contenedorDocumentos0'>
                            <img id="visualFotosID" src="/img/subir.png" alt="" width="150px" height="150px">
                            <p id="textoID" class="col-7 mx-auto">Fotos del ID (Ambos lados)*</p>
                        </div>
                        <input type="hidden" id="tipoID" name="tipoID" wire:model="tipoID" value="{{$tipoID}}">
                        <div id="contenedor2" class="align-self-center col-sm-3 text-center position-relative" wire:key='contenedorDocumentos3'>
                            <img id="visualCIF" src="/img/subir.png" alt="" width="150px" height="150px">
                            <p id="textoCIF" class="col-7 mx-auto">Fotos CSF *</p>
                        </div>
                        <input type="hidden" id="tipoCSF" name="tipoCSF" wire:model="tipoCSF" value="{{$tipoCSF}}">
                    </div>

                    <div class="d-md-flex justify-content-center col-12 mx-auto">
                        <div id="contenedor3" class="me-md-5 col-sm-3 text-center position-relative" wire:key='contenedorDocumentos1'>
                            <img id="visualPolizaSeguros" src="/img/subir.png" alt="" width="150px" height="150px">
                            <p id="textoPoliza" class="col-7 mx-auto">Póliza de seguros para terceros</p>
                        </div>
                        <input type="hidden" id="tipoSegurosTerceros" name="tipoSegurosTerceros" wire:model="tipoSegurosTerceros" value="{{$tipoSegurosTerceros}}">
                        <div id="contenedor4" class="ms-md-5 col-sm-3 text-center position-relative" wire:key='contenedorDocumentos2'>
                            <img id="visualComprobanteDomicilio" src="/img/subir.png" alt="" width="150px" height="150px">
                            <p id="textoDireccion" class="col-7 mx-auto">Comprobante de dirección fiscal*</p>
                        </div>
                        <input type="hidden" id="tipoDireccionFiscal" name="tipoDireccionFiscal" wire:model="tipoDireccionFiscal" value="{{$tipoDireccionFiscal}}">
                    </div>
                    <div class="text-center my-3">
                        @error('fotosID') <span class="bg-danger text-white rounded-3 p-3">{{ $message }}</span> @enderror
                        @error('polizaSeguros') <span class="bg-danger text-white rounded-3 p-3">{{ $message }}</span> @enderror
                        @error('comprobanteDireccionFiscal') <span class="bg-danger text-white rounded-3 p-3">{{ $message }}</span> @enderror
                        @error('cif') <span class="bg-danger text-white rounded-3 p-3">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-5 mx-md-auto my-5">
                    <div class="d-flex justify-content-center">
                        <input wire:model="checkBoxTerminos" type="checkbox" name="checkBoxTerminos" id="checkBoxTerminos" class="col-1 me-2">
                        <p id="aceptarTerminos">Acepta términos y condiciones.</p>

                        <a href="/contratos/{{__('Anfitrión 4Friends pdf') }}"> <i class="bi bi-box-arrow-up-right ms-2"></i> </a>
                    </div>
                </div>
                <div class="text-center">
                    <button id="btnEnviar" type="submit" class="align-middle btnEnviar">
                        <div class="spinner-border spinner-border-sm" role="status" style="display: none;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span id="textoBoton" style="color:white!important;">Enviar</span>
                    </button>
                </div>
                <div class="text-center">

                    <i class="fa fa-exclamation ms-2" id="icono"> </i>
                    <span id="comisiones"> La comisíon 4Friends es del 16-19% del PVP mas 4% de pagos interbancarios.</span>
                </div>
            </section>
        </form>
    </main>


    @push('scripts')
        <script type="module">
            import Ajaxs from '/js/general/ajax.js';

            $('#formRegistroSocio').submit(function (e) {
                e.preventDefault();
                modoCargando(true);
                var ruta = $('#formRegistroSocio').attr('action');
                var datos = $("#formRegistroSocio").serializeArray();

                if(true/*validarVaciosYFormatos(
                ['El nombre de la actividad', 'La categoria', 'El estado de la actividad', 'La ciudad de la actividad','El minimo de personas', 'El maximo de personas', 'Lenguaje de guías', 'Nombres', 'Apellidos', 'Correo electronico', 'Telefono', 'Contraseña', 'Repetir contraseña', 'RFC', 'Tipo de persona', 'Razon social', 'Nombre comercial', 'Sotfware de terceros', 'Tipo de operador', 'Nombre software de terceros', 'Calles', 'Número exterior', 'Colonia', 'Código postal', 'Estado', 'Ciudad', 'Pertenencia a AMAV', 'Representante AMAV', 'Fotos ID', 'Póliza de seguros', 'Comprobante de dirección', 'Fotos CIF', 'Términos y condiciones'],
                ['nombreActividad', 'categoriaActividad', 'filtroEstadoTour', 'ciudadActividad', 'minPersona', 'maxPersona', 'guiasIngles', ''],
                [idErrores],
                [tiposEsperados],
                [obligatorios])*/){
                    $.when(
                        Ajaxs.funcionAjaxVacia(ruta, datos, 'POST', respuesta,  erroresAjaxForms)
                    ).done(function () {
                            return false;
                        }
                    );
                }
                return false;
            });

            function respuesta(response){
                console.log(response);
                if(response[0] === 1){
                    swal.fire({
                        icon:'success',
                        title:'Exito',
                        text:response[1]
                    }).then((result) => {
                        window.location.href = '{{route("index")}}';
                    });
                }
                else if(response[0] === 0){
                    swal.fire({
                        icon:'error',
                        title:'Error',
                        text:response[1]
                    });
                    modoCargando(false);
                }
            }

            Livewire.on('cargandoImg', (idElemento) => {
                $('#'+idElemento[0]).parent().prepend('<div class="imgCargada" wire:key="contenedorDocumentos'+idElemento[1]+'"></div>');
                $('div#'+idElemento[2]+' > div').remove('.imgCargando');
                $('div.imgCargada').bind('click', function (e) {
                    var id = $(this).parent().children('img').attr('id');
                    Livewire.emit('borrarImg', id);
                });
            });
            $('#visualFotosID').click(function (e) {
                e.preventDefault();
                $('#visualFotosID').parent().prepend('<div class="imgCargando" wire:key="cargandoImgLoad0"><div class="spinner-border text-secondary mt-5" wire:key="cargandoImgLoad0"><span class="visually-hidden" wire:key="cargandoImgLoad0">Loading...</span></div></div>');
                $('#fotosID').trigger('click');
            });
            $('#visualCIF').click(function (e) {
                e.preventDefault();
                $('#visualCIF').parent().prepend('<div class="imgCargando" wire:key="cargandoImgLoad1"><div class="spinner-border text-secondary mt-5" wire:key="cargandoImgLoad1"><span class="visually-hidden" wire:key="cargandoImgLoad1">Loading...</span></div></div>');
                $('#cif').trigger('click');
            });
            $('#visualPolizaSeguros').click(function (e) {
                e.preventDefault();
                $('#visualPolizaSeguros').parent().prepend('<div class="imgCargando" wire:key="cargandoImgLoad2"><div class="spinner-border text-secondary mt-5" wire:key="cargandoImgLoad2"><span class="visually-hidden" wire:key="cargandoImgLoad2">Loading...</span></div></div>');
                $('#polizaSeguros').trigger('click');
            });
            $('#visualComprobanteDomicilio').click(function (e) {
                e.preventDefault();
                $('#visualComprobanteDomicilio').parent().prepend('<div class="imgCargando" wire:key="cargandoImgLoad3"><div class="spinner-border text-secondary mt-5" wire:key="cargandoImgLoad3"><span class="visually-hidden" wire:key="cargandoImgLoad3">Loading...</span></div></div>');
                $('#comprobanteDireccionFiscal').trigger('click');
            });
            Livewire.on('cancelarImg', (response) => {
                $('div#'+response+' > div').remove('.imgCargada');
                switch (response) {
                    case 'contenedor1':
                        $('#fotosID').val('');
                        break;
                    case 'contenedor2':
                        $('#cif').val('');
                        break;
                    case 'contenedor3':
                        $('#polizaSeguros').val('');
                        break;
                    case 'contenedor4':
                        $('#comprobanteDireccionFiscal').val('');
                        break;
                }
            });
        </script>
        <script src="/js/homePage/traducir.js"></script>
    @endpush
</div>
