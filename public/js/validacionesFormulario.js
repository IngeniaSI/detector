var cumpleFormulario =true;
/////Validar si el campos si esta vacio al perder el foco e
$('#fechaRegistro').blur(function(){
    if($('#fechaRegistro').val()==''){
        // Swal.fire({
        // title: "Campo Vacio",
        // text: "Verifica el campo",
        // icon: "info"
        // });
    }
    else{
        $('#fechaRegistroError').remove();
    }
});
$('#folio').blur(function(){
    if($('#folio').val()==''){
        // Swal.fire({
        //     title: "Campo Vacio",
        //     text: "Verifica el campo",
        //     icon: "info"
        //     });
    }
    else{
        $('#folioError').remove();
    }
});
$('#colonias').change(function(){
    if($('#colonias').val() == 0){
        // Swal.fire({
        //     title: "Campo sin Seleccionar",
        //     text: "Verifica el campo",
        //     icon: "info"
        //     });
    }
    else{
        $('#coloniaError').remove();
    }
});
$('#codigoPostal').blur(function(){
    if($('#codigoPostal').val().length != 5){
        // Swal.fire({
        //     title: "Campo sin Seleccionar",
        //     text: "Verifica el campo",
        //     icon: "info"
        //     });
    }
    else{
        $('#codigoPostalError').remove();
    }
});
$('#municipios').change(function(){
    if($('#municipios').val() == 0){
        // Swal.fire({
        //     title: "Campo sin Seleccionar",
        //     text: "Verifica el campo",
        //     icon: "info"
        //     });
    }
    else{
        $('#municipioError').remove();
    }
});

$('#apellido_paterno').blur(function(){
    if($('#apellido_paterno').val()==''){
        Swal.fire({
            title: "Campo Vacio",
            text: "Verifica el campo",
            icon: "info"
            });
    }
    else{
        $('#apellidoPaternoError').remove();
    }
});

$('#apellido_materno').blur(function(){
    if($('#apellido_materno').val()==''){
        // Swal.fire({
        //     title: "Campo Vacio",
        //     text: "Verifica el campo",
        //     icon: "info"
        //     });
    }
    else{
        $('#apellidoMaternoError').remove();
    }
});
$('#nombre').blur(function(){
    if($('#nombre').val()==''){
        Swal.fire({
            title: "Campo Vacio",
            text: "Verifica el campo",
            icon: "info"
            });
    }
    else{
        $('#nombresError').remove();
    }
});
$('#telefonoFijo').blur(function(){
    if($('#telefonoFijo').val()==''){
        // Swal.fire({
        //     title: "Campo Vacio",
        //     text: "Verifica el campo",
        //     icon: "info"
        //     });
    }
    else{
        $('#telefonoFijoError').remove();
    }
});

$('#correo').blur(function(){
    if($('#correo').val()==''){
        // Swal.fire({
        //     title: "Campo Vacio",
        //     text: "Verifica el campo",
        //     icon: "info"
        //     });
    }
    else{
        $('#correoError').remove();
    }
});

$('#calle').blur(function(){
    if($('#calle').val()==''){
        // Swal.fire({
        //     title: "Campo Vacio",
        //     text: "Verifica el campo",
        //     icon: "info"
        //     });
    }
    else{
        $('#calleError').remove();
    }
});

$('#numeroExterior').blur(function(){
    if($('#numeroExterior').val()==''){
        // Swal.fire({
        //     title: "Campo Vacio",
        //     text: "Verifica el campo",
        //     icon: "info"
        //     });
    }
    else{
        $('#numeroExteriorError').remove();
    }
});

//Validar Fecha de registro es mayor a la fecha actual
document.getElementById("fechaRegistro").addEventListener("change",()=>{
    let d = document.getElementById("fechaRegistro").valueAsDate.getTime();
    let ahora = new Date().getTime()
    if(ahora - d >= 0){console.log("FECHA REGISTRO  CORRECTA")}else{
        Swal.fire({
            title: "Fecha de Registro supera la fecha actual",
            text: "Verifica el campo",
            icon: "error"
            });
    }
  })

//Validar Folio Max 7 min 0 caracterees
var input = document.getElementById('folio');
        input.addEventListener('input', function () {
            if (this.value.length > 7)
                this.value = this.value.slice(0, 7);
        })

//validar apellido solo letras y espacios
//Se valido en el html apellidos y nombre

//Se cambio  check box por select para el genero


//Validar Fecha de nacimineto es mayor a la fecha actual
document.getElementById("fechaNacimiento").addEventListener("change",()=>{
    let d = document.getElementById("fechaNacimiento").valueAsDate.getTime();
    let ahora = new Date().getTime()
    if(ahora - d >= 0){console.log("FECHA NACIENMIENTO  CORRECTA")}else{
        Swal.fire({
            title: "Fecha de Nacimiento supera la fecha actual",
            text: "Verifica el campo",
            icon: "error"
            });
    }
  })


// Se valida el telefono fijo
var inputCelular = document.getElementById('telefonoFijo');
inputCelular.addEventListener('input', function () {
            if (this.value.length > 12)
                this.value = this.value.slice(0, 12);
        })

// Validar Correo si esta correcto
$("#correo").blur(function () {
    validarEmail($("#correo").val())
});
function validarEmail(valor) {
    emailRegex = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;

    if (emailRegex.test(valor)) {
        $("#correo").css({ "background": "rgb(82, 179, 126)" })
  } else {
        $("#correo").css({ "background": "red" })
  }
}

// Validar claveElectoral si esta correcto
$("#claveElectoral").blur(function () {
    validarClaveElectoral($("#claveElectoral").val())
});
function validarClaveElectoral(valor) {
    emailRegex = /^([A-Z]{6})(\d{8})([B-DF-HJ-NP-TV-Z]{1})(\d{3})$/i;

    if (emailRegex.test(valor)) {
        $("#claveElectoral").css({ "background": "rgb(82, 179, 126)" });
        $('#claveElectoralError').remove();
  } else {
        $("#claveElectoral").css({ "background": "red" })
  }
}

// Validar curp si esta correcto
$("#curp").blur(function () {
    validarCurp($("#curp").val())
});
function validarCurp(valor) {
    emailRegex = /^([A-Z]{4})(\d{6})([HM])([A-Z]{5})([0-9A-Z]{2})$/i;

    if (emailRegex.test(valor)) {
        $("#curp").css({ "background": "rgb(82, 179, 126)" });
        $('#curpError').remove();
        var fechaNacimiento = $('#fechaNacimiento').val();
        if(fechaNacimiento == ''){
            var fecha = new Date(valor.substring(4, 6), (valor.substring(6, 8)) -1, valor.substring(8, 10));
            var fechaFormato = fecha.toISOString().substring(0, 10);
            $('#fechaNacimiento').val(fechaFormato);
            $('#fechaNacimiento').trigger('change');
        }
  } else {
        $("#curp").css({ "background": "red" })
  }
}

function validar() {

    if(cumpleFormulario ==true){
        $("#BotonAgregarPersona").prop("disabled", true);
        $("#BotonValidador").prop("disabled", true);
        //AVISO DE CARGANDO
        let timerInterval;
        Swal.fire({
        title: "CARGANDO...",
        html: "",
        timer: 10000,
        timerProgressBar: true,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
            const timer = Swal.getPopup().querySelector("b");
            timerInterval = setInterval(() => {
            timer.textContent = `${Swal.getTimerLeft()}`;
            }, 100);
        },
        willClose: () => {
            clearInterval(timerInterval);
        }
        }).then((result) => {
        /* Read more about handling dismissals below */
        if (result.dismiss === Swal.DismissReason.timer) {
            console.log("I was closed by the timer");
        }
        });
        $("#BotonAgregarPersona").prop("disabled", false);
        $("#BotonAgregarPersona").click();

    }else{

        Swal.fire({
        title: "Varifica los campos",
        text: "",
        icon: "info"
        });
    }



}




