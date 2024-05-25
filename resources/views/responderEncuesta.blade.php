<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Responder encuesta: {{$nombreEncuesta}}</title>
        <link href="{{ asset('Plantilla/css/styles.css') }}" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <style>
        body, html {
            height: 100%;
            background-repeat: no-repeat;
            background: url('{{ asset('Plantilla/assets/img/fondo.jpg') }}') no-repeat center center fixed;
            background-size: 100% 100%;
        }
    </style>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container-fluid px-4">
                        <div class="card my-4">
                            <div class="card-header">
                                <h3 class="mt-3">{{$nombreEncuesta}}</h3>
                            </div>
                            <div class="card-body">
                                <form id="rendered-form-container" action="{{route('encuestas.contestarEncuesta', $idEncuesta)}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="usuarioRelacionado" value="{{$codigoPromotor}}">
                                    <input type="hidden" name="origen" value="{{$origen}}">
                                    <div id="rendered-form"></div>
                                    <button id="submit-form" class="btn btn-primary">Enviar respuestas</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
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
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            @if (session()->has('mensajeExito'))
            Swal.fire({
                'title':"Éxito",
                'text':"{{session('mensajeExito')}}",
                'icon':"success"
            });
            @endif
        </script>
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
        <script src="https://formbuilder.online/assets/js/form-builder.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.4.0/highlight.min.js"></script>
        <script src="https://formbuilder.online/assets/js/form-render.min.js"></script>


            <script text="text/javascript">
                $(document).ready(function() {
                    Swal.fire({
                        title: 'Cargando...',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        html: '<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>'
                    });
                    $.when(
                            $.ajax({
                                type: "get",
                                url: "{{url('/')}}/encuestas/cargar-encuesta-{{$idEncuesta}}",
                                data: [],
                                contentType: "application/x-www-form-urlencoded",
                                success: function (response) {
                                    var formBuilder = $('#form-builder').formBuilder();
                                    var formData = response;
                                    $('#rendered-form').formRender({formData: formData});
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
                            Swal.close();
                        });
                });

                $('#submit-form').click(function() {
                    // var serializedData = $('#rendered-form-container').serializeArray();
                    Swal.fire({
                        title: 'Cargando...',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        html: '<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>'
                    });
                });
            </script>

    </body>
</html>








