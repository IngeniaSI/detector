@extends('Pages.plantilla')

@section('tittle')
    Promotores
@endsection


@section('cuerpo')
<link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/tutorials/timelines/timeline-1/assets/css/timeline-1.css">


    <div class="container-fluid px-4">
        <h1 class="mt-4">Promotores</h1>
        <div class="card mb-4">
            <div class="card-body">

                <table id="tabla" class="table table-striped table-bordered " style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre Promotor</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="/js/generador-contrasenias.js" text="text/javascript"></script>
    <script src="https://formbuilder.online/assets/js/form-builder.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.4.0/highlight.min.js"></script>
    <script src="https://formbuilder.online/assets/js/form-render.min.js"></script>


    <script text="text/javascript">
        var fbEditor, formBuilder, fbEditor2, formBuilder2,fbEditorPrevio, formBuilderPrevio;
        var encuestaACompartir = 0;
        var table;
        function efectoCargando(){
            Swal.fire({
                title: 'Cargando...',
                allowOutsideClick: false,
                showConfirmButton: false,
                html: '<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>'
            });
        }

        $(document).ready(function () {
            // efectoCargando();
            table = $('#tabla').DataTable({
                order: [[0, 'desc']],
                scrollX: true,
                lengthChange: true,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json',
                },
                "processing": true,
                "serverSide": true,
                ajax: {
                    url: "{{route('promotores.cargarPromotores')}}",
                    data: function(d) {
                        d.fechaInicio = $('#fechaInicioFiltro').val();
                        d.fechaFin = $('#fechaFinFiltro').val();
                    }
                },
                columns: [
                    { data: 'id' },
                    { data: 'nombre_completo'},
                    { data: null,
                        render: function(data, type, row){
                            var botones ='<button class="btn btn btn-success" ><i class="fas fa-file-excel me-1"></i>Exportar Excel</button>';
                            return botones;
                        }},
                    // Agrega más columnas según tus datos
                ]
            });
        });
    </script>
@endsection
