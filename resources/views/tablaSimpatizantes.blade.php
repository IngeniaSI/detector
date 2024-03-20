<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CRUD simpatizantes</title>
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
    <a href="{{route('agregarSimpatizante.index')}}">
        <button>Agregar simpatizante</button>
    </a>
    {{-- TABLA DE USUARIOS --}}
    <table id="tablaUsuarios">
        <thead>
            <th>Nombre completo</th>
            <th>Correo</th>
            <th>Telefono celular</th>
        </thead>
        <tbody>

        </tbody>
    </table>
    @error('errorBorrar')
        <h5>{{$message}}</h5>
    @enderror

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
                url: "{{route('crudSimpatizantes.inicializar')}}",
                data: [],
                contentType: "application/x-www-form-urlencoded",
                success: function (response) {
                    if(response.length == 0){
                        var nuevaFila = $('<tr>').append(
                            $('<td>').attr('colspan', 99).text('Sin registros')
                        );
                        $('#tablaUsuarios tbody').append(nuevaFila);
                    }
                    $.each(response, function (index, elemento) {
                        var nuevaFila = $('<tr>').append(
                            $('<td>').text(elemento.nombres + ' ' + elemento.apellido_paterno + ' ' + elemento.apellido_materno),
                            $('<td>').text(elemento.correo),
                            $('<td>').text(elemento.telefono_celular)
                        );
                        $('#tablaUsuarios tbody').append(nuevaFila);
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
    </script>
</body>
</html>
