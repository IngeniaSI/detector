var cumpleFormulario =true;
/////Validar si el campos si esta vacio al perder el foco e
$('#fechaRegistro').blur(function(){
    if($('#fechaRegistro').val()==''){
        Swal.fire({
            title: "Campo Vacio",
            text: "Verifica el campo",
            icon: "info"
            });
    }
});
$('#folio').blur(function(){
    if($('#folio').val()==''){
        Swal.fire({
            title: "Campo Vacio",
            text: "Verifica el campo",
            icon: "info"
            });
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
});

$('#apellido_materno').blur(function(){
    if($('#apellido_materno').val()==''){
        Swal.fire({
            title: "Campo Vacio",
            text: "Verifica el campo",
            icon: "info"
            });
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
});
$('#telefonoCelular').blur(function(){
    if($('#telefonoCelular').val()==''){
        Swal.fire({
            title: "Campo Vacio",
            text: "Verifica el campo",
            icon: "info"
            });
    }
});
$('#telefonoFijo').blur(function(){
    if($('#telefonoFijo').val()==''){
        Swal.fire({
            title: "Campo Vacio",
            text: "Verifica el campo",
            icon: "info"
            });
    }
});

$('#correo').blur(function(){
    if($('#correo').val()==''){
        Swal.fire({
            title: "Campo Vacio",
            text: "Verifica el campo",
            icon: "info"
            });
    }
});

$('#calle').blur(function(){
    if($('#facebook').val()==''){
        Swal.fire({
            title: "Campo Vacio",
            text: "Verifica el campo",
            icon: "info"
            });
    }
});

$('#numeroExterior').blur(function(){
    if($('#facebook').val()==''){
        Swal.fire({
            title: "Campo Vacio",
            text: "Verifica el campo",
            icon: "info"
            });
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

// Se valida el telefono celular
var inputCelular = document.getElementById('telefonoCelular');
inputCelular.addEventListener('input', function () {
            if (this.value.length > 12)
                this.value = this.value.slice(0, 12);
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
        
function validar() {
    
    if(cumpleFormulario ==true){
        $("#BotonAgregarPersona").prop("disabled", true);
        $("#BotonValidador").prop("disabled", true);
        //AVISO DE CARGANDO
        let timerInterval;
        Swal.fire({
        title: "CARGANDO...",
        html: "",
        timer: 5000,
        timerProgressBar: true,
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




