<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Matrix</title>
</head>
<body>
    <select id="modificarRolUsuario" name="rolUsuario" @disabled(old('rolUsuario') == null)>
        @foreach ($roles as $rol)
            <option value="{{$rol->name}}">{{str_replace('_', ' ', $rol->name)}}</option>
        @endforeach
    </select>
</body>
</html>
