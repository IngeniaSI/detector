@extends('Pages.plantilla')

@section('tittle')
    Mapa de Personas
@endsection

@section('cuerpo')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.3/dist/leaflet.css" integrity="sha512-07I2e+7D8p6he1SIM+1twR5TIrhUQn9+I6yjqD53JQjFiMf8EtC93ty0/5vJTZGF8aAocvHYNEDJajGdNx1IsQ==" crossorigin="" />
<link rel="stylesheet" href="https://leaflet.github.io/Leaflet.markercluster/example/screen.css" />
<link rel="stylesheet" href="https://leaflet.github.io/Leaflet.markercluster/dist/MarkerCluster.css" />
	<link rel="stylesheet" href="https://leaflet.github.io/Leaflet.markercluster/dist/MarkerCluster.Default.css" />
<h1 class="mt-4">Mapa de Personas</h1>

    {{-- <iframe style="width: 100%;height: 700px;"
    id="inlineFrameExample"
    title="Inline Frame Example"
    width="300"
    height="200"
    src="/Plantilla/mapa2.html">
</iframe> --}}

<div id="map" style="width: 100%;"></div>

@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet-src.js" integrity="sha512-WXoSHqw/t26DszhdMhOXOkI7qCiv5QWXhH9R7CgvgZMHz1ImlkVQ3uNsiQKu5wwbbxtPzFXd1hK4tzno2VqhpA==" crossorigin=""></script>
<script src="https://leaflet.github.io/Leaflet.markercluster/dist/leaflet.markercluster-src.js"></script>
	<script src="https://leaflet.github.io/Leaflet.markercluster/example/realworld.388.js"></script>
<script type="text/javascript">

    var addressPoints = @json($domicilioArray);
        var tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Points &copy 2012 LINZ'
            }),
            latlng = L.latLng(24.13571, -110.308914);

        var map = L.map('map', {center: latlng, zoom: 13, layers: [tiles]});

        var markers = L.markerClusterGroup();

        for (var i = 0; i < addressPoints.length; i++) {
            var a = addressPoints[i];
            var marker = L.marker(new L.LatLng(a[0], a[1]));
            markers.addLayer(marker);
        }

        map.addLayer(markers);

    </script>
@endsection
