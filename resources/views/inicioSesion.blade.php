<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
</head>
<body>
   
</body>
</html>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Detector</title>
        <link href="{{ asset('Plantilla/css/styles.css') }}" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <style>
        body, html {
    height: 100%;
    background-repeat: no-repeat;
    background: url({{ asset('Plantilla/assets/img/fondo.jpg') }}) no-repeat center center fixed;
    background-size: 100% 100%;
}

    </style>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Detector</h3></div>
                                    <div class="card-body">
                                    <form action="{{route('login.comprobando')}}" method="post" class="form-floating mb-3">
                                        @csrf
                                        <div class="form-floating mb-3">
                                            <input class="form-control" type="email" name="correo" id="correo" min="3"  />
                                            <label for="inputEmail">Correo Electronico</label>
                                        </div>
                                        

                                        <div class="form-floating mb-3">
                                                <input class="form-control" type="password" name="contrasenia" id="contrasenia"  />
                                                <label for="inputPassword">Contraseña</label>
                                        </div>
                                        
                                        
                                        <center><button class="btn btn-primary">Iniciar sesion</button></center>
                                        @error('email')
                                            <h4>{{$message}}</h4>
                                        @enderror
                                    </form>
                                        
                                    </div>
                                    <!-- <div class="card-footer text-center py-3">
                                        <div class="small"><a href="register.html">Need an account? Sign up!</a></div>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; IngeniaIS 2024</div>
                            
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="{{ asset('Plantilla/js/scripts.js') }}"></script>
    </body>
</html>


