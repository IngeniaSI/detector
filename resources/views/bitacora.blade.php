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
                            <tr>
                                <td>13/Marzo/2024 9:55PM</td>
                                <td>Inicio Sesion Eduardo Reyes Mtz</td>
                                <td>/Bitacora</td>
                                <td>192.235.434.222</td>
                                <td>234</td>
                            </tr>
                            <tr>
                                <td>13/Marzo/2024 10:01PM</td>
                                <td>Consulto Bitacora</td>
                                <td>/login</td>
                                <td>192.235.434.222</td>
                                <td>234</td>
                            </tr>
                    </tbody>
                </table>
                

@endsection

@section('scripts')
<script>
   $(document).ready(function() {
	    var table = $('#example').DataTable( {
	        lengthChange: true,
            language: {
             url: 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json',
         },
	        buttons: [ 'copy', 'excel', 'csv', 'pdf', 'colvis' ]
	    } );
	 
	    table.buttons().container()
	        .appendTo( '#example_wrapper .col-md-6:eq(0)' );
	} );
</script>
@endsection
