<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
</head>
<body>
    <form action="{{route('login.comprobando')}}" method="post">
        @csrf
        <label for="">Correo</label>
        <input type="email" name="correo" id="correo" min="3">
        <label for="">Contraseña</label>
        <input type="password" name="contrasenia" id="contrasenia">
        <button>Iniciar sesion</button>
        @error('email')
            <h4>{{$message}}</h4>
        @enderror
    </form>
</body>
</html>
