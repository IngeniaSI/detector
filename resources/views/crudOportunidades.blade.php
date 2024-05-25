@extends('Pages.plantilla')

@section('tittle')
    Oportunidades
@endsection


@section('cuerpo')
<link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/tutorials/timelines/timeline-1/assets/css/timeline-1.css">
    <!-- Modal Historico-->
<div class="modal fade" id="modalHistorico" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Historico de Nombre Persona</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <center> <h5 class="modal-title" >Nombre Oportunidad</h5></center>
     
<section class="bsb-timeline-1 py-5 py-xl-8">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-10 col-md-8 col-xl-6">

        <ul class="timeline">

          <li class="timeline-item" >
            <div class="timeline-body">
              <div class="timeline-content">
                <div class="card border-0">
                  <div class="card-body p-0">
                    <h5 class="card-subtitle text-secondary mb-1">01/05/2024 11:34</h5>
                    <h2 class="card-title mb-3">Pendiente</h2>
                  </div>
                </div>
              </div>
            </div>
          </li>
          <li class="timeline-item">
            <div class="timeline-body">
              <div class="timeline-content">
                <div class="card border-0">
                  <div class="card-body p-0">
                    <h5 class="card-subtitle text-secondary mb-1">01/05/2024 11:34</h5>
                    <h2 class="card-title mb-3">Iniciado</h2>
                  </div>
                </div>
              </div>
            </div>
          </li>


        </ul>

      </div>
    </div>
  </div>
</section>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Oportunidades</h1>
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-center">
                   
                        <button class="btn btn btn-success" ><i class="fas fa-file-excel me-1"></i>Exportar Excel</button>
                    
                </div>
            </div>
            <div class="card-body">
                
                <table id="tabla" class="table table-striped table-bordered " style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre Oportunidad</th>
                            <th>Nombre Persona</th>
                            <th>Estatus</th>
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
        $(document).ready(function () {
            
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
                    url: "{{route('encuestas.cargar')}}",
                    data: function(d) {
                        d.fechaInicio = $('#fechaInicioFiltro').val();
                        d.fechaFin = $('#fechaFinFiltro').val();
                    }
                },
                columns: [
                    { data: 'id' },
                    { data: 'nombre'},
                    { data: 'nombre'},
                    { data: 'estatus' ,
                        render: function(data, type, row){
                            var select =
                            '<select name="select" class="form-control"> <option value="Pendiente">Pendiente</option> <option value="Iniciado">Iniciado</option> <option value="Compromiso">Compromiso</option> <option value="Cumplido">Cumplido</option><option value="Perdido">Perdido</option></select>';
                            return select;
                        }},
                    { data: null,
                        render: function(data, type, row){
                            var botones =
                            
                                '<button id="btnHistorico_'+data.id+'" class ="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalHistorico">'+
                                        '<i class="fas fa-clock me-1">'+
                                    '</i>&nbsp;Historico'+
                                    '</button>';
                                    var botones =
                            
                                botones +='<button class="btn btn btn-success" ><i class="fas fa-file-excel me-1"></i>Exportar Excel</button>';
                            return botones;
                        }},
                    // Agrega más columnas según tus datos
                ]
            });

           
        });

        
        

       
    </script>
@endsection
