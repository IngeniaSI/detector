@extends('Pages.plantilla')

@section('tittle')
    Mapa Geoespacial
@endsection

@section('cuerpo')
<h1 class="mt-4">Mapa Geoespacial</h1>
                        
                        <iframe style="width: 100%;height: 700px;"
  id="inlineFrameExample"
  title="Inline Frame Example"
  width="300"
  height="200" 
  src="/Plantilla/mapa2.html">
</iframe>


@endsection

@section('scripts')

@endsection
