@extends('Pages.plantilla')

@section('tittle')
    Bitacora
@endsection

@section('cuerpo')

    <div class="container-fluid px-4">
        <h1 class="mt-4">Bítacora</h1>
        
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Resultados
            </div>
            <div class="card-body">
               
                <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                                <th>Fecha y Hora</th>
                                <th>Acción</th>
                                <th>Url</th>
                                <th>ip</th>
                                <th>Id del Usuario</th>
                        </tr>
                    </thead>
                    <tbody>
                           
                    </tbody>
                </table>
                

@endsection

@section('scripts')
<script>
   $(document).ready(function() {
	    var table = $('#example').DataTable( {
	        lengthChange: true,
            "bDestroy": true,
            language: {
             url: 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json',
         },
	        buttons: [ 'copy', 'excel', 'csv', 'pdf', 'colvis' ]
	    } );
	 
	    table.buttons().container()
	        .appendTo( '#example_wrapper .col-md-6:eq(0)' );
	} );

    @foreach ($query as $rol)
    $('#example').DataTable().row.add(['{{$rol->created_at}}', '{{$rol->accion}}', '{{$rol->url}}', '{{$rol->ip}}', '{{$rol->user_id}}']).draw();
    @endforeach
   
</script>
@endsection
