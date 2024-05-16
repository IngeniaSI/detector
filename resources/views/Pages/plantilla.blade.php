<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>@yield('tittle')</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="{{ asset('Plantilla/css/styles.css') }}" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="index.html">Detector</a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <div class="input-group">
                    <!-- <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                    <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button> -->
                </div>
            </form>
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i> {{$user->nombre.' '.$user->apellido_paterno.' '.$user->apellido_materno}}</a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <!-- <li><a class="dropdown-item" href="#!">Settings</a></li>
                        <li><a class="dropdown-item" href="#!">Activity Log</a></li> -->
                        <li><hr class="dropdown-divider" /></li>
                        <li>
                        <form action="{{route('logout')}}" method="post">
                            @csrf
                            <button class="dropdown-item" >Cerrar sesion</button>
                        </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Menú</div>
                            @can('estadistica.index')
                                <a class="nav-link" href="{{url('/')}}/estadistica">
                                    <div class="sb-nav-link-icon"><i class="fas fa-chart-bar"></i></div>
                                    Estadística
                                </a>
                            @endcan
                            @can('encuestas.index')
                                <a class="nav-link" href="{{route('encuestas.index')}}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                    Encuestas
                                </a>
                                
                            @endcan
                            <a class="nav-link" href="{{url('/')}}/respuestasEncuestas">
                                    <div class="sb-nav-link-icon"><i class="fas fa-file"></i></div>
                                    Respuestas de las Encuestas
                            </a>
                            {{-- @can('agregarSimpatizante.index')
                                <a class="nav-link" href="{{url('/')}}/simpatizantes/agregar">
                                    <div class="sb-nav-link-icon"><i class="fas fa-file"></i></div>
                                    Agregar Persona
                                </a>
                            @endcan --}}
                            @can('crudSimpatizantes.index')
                                <a class="nav-link" href="{{url('/')}}/simpatizantes">
                                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                    Tabla de Persona
                                </a>
                            @endcan
                            @can('mapa.index')
                                <a class="nav-link" href="{{url('/')}}/mapa">
                                    <div class="sb-nav-link-icon"><i class="fas fa-map"></i></div>
                                    Mapa de Personas
                                </a>
                            @endcan
                            @can('crudUsuarios.index')
                                <a class="nav-link" href="{{url('/')}}/gestor-usuarios">
                                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                                    Usuarios del Sistema
                                </a>
                            @endcan

                            @can('bitacora.index')
                            <a class="nav-link" href="{{url('/')}}/bitacora">
                                <div class="sb-nav-link-icon"><i class="fas fa-info"></i></div>
                                Bitácora
                            </a>
                            @endcan
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Usuario:</div>
                        {{$role}}
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>

                    @yield('cuerpo')

                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; IngeniaSI 2024</div>

                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="{{ asset('Plantilla/js/scripts.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.bootstrap4.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.colVis.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap4.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
        @if (session()->has('mensajeExito'))
            Swal.fire({
                'title':"Éxito",
                'text':"{{session('mensajeExito')}}",
                'icon':"success"
            });
        @endif
        @if (session('nivelAccesoDenegado'))
            Swal.fire({
                'title':"Acceso denegado",
                'text':"{{ session('nivelAccesoDenegado') }}",
                'icon':"warning"
            });
        @endif
        </script>
        @yield('scripts')
    </body>




</html>
