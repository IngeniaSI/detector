<?php

namespace App\Http\Controllers;

use App\Models\distritoFederal;
use App\Models\distritoLocal;
use App\Models\domicilio;
use App\Models\entidad;
use App\Models\persona;
use App\Models\seccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class mapaController extends Controller
{
    public function index(){
        $user = auth()->user();

        // $user = auth()->user();
        // // return $user;
        // $niveles = isset($user->niveles) ? explode( ',', $user->niveles) : null;
        // return $niveles; //APLICAR TRIM A CADA NIVEL

        $user = auth()->user();
        switch ($user->nivel_acceso) {
            case 'TODO':
                    //HACER CONSULTA SIN FILTROS
                    $seccionesParaBuscar = seccion::pluck('id')->toArray();
                break;
            case 'ENTIDAD':
                    //HACER CONSULTA FILTRAR PERSONAS QUE SU IDENTIFICACION
                    //PERTENEZCA A LA LISTA DE SECCIONES PERTENECIENTES A LAS ENTIDADES SELECCIONADAS
                $nivelesConAcceso = explode(',', $user->niveles);
                $seccionesParaBuscar = entidad::whereIn('entidads.id', $nivelesConAcceso)
                ->join('distrito_federals', 'entidads.id', '=','distrito_federals.entidad_id')
                ->join('municipios', 'distrito_federals.id', '=','municipios.distrito_federal_id')
                ->join('distrito_locals', 'municipios.id', '=','distrito_locals.municipio_id')
                ->join('seccions', 'distrito_locals.id', '=','seccions.distrito_local_id')
                ->pluck('seccions.id')
                ->toArray();

                break;
            case 'DISTRITO FEDERAL':
                    //HACER CONSULTA FILTRAR PERSONAS QUE SU IDENTIFICACION
                    //PERTENEZCA A LA LISTA DE SECCIONES PERTENECIENTES A LOS DISTRITOS FEDERALES SELECCIONADAS
                $nivelesConAcceso = explode(',', $user->niveles);
                $seccionesParaBuscar = distritoFederal::whereIn('distrito_federals.id', $nivelesConAcceso)
                ->join('municipios', 'distrito_federals.id', '=','municipios.distrito_federal_id')
                ->join('distrito_locals', 'municipios.id', '=','distrito_locals.municipio_id')
                ->join('seccions', 'distrito_locals.id', '=','seccions.distrito_local_id')
                ->pluck('seccions.id')
                ->toArray();

                break;
            case 'DISTRITO LOCAL':
                    //HACER CONSULTA FILTRAR PERSONAS QUE SU IDENTIFICACION
                    //PERTENEZCA A LA LISTA DE SECCIONES PERTENECIENTES A LOS DISTRITOS LOCALES SELECCIONADAS
                $nivelesConAcceso = explode(',', $user->niveles);
                $seccionesParaBuscar = distritoLocal::whereIn('distrito_locals.id', $nivelesConAcceso)
                ->join('seccions', 'distrito_locals.id', '=','seccions.distrito_local_id')
                ->pluck('seccions.id')
                ->toArray();

                break;
            case 'SECCION':
                    //HACER CONSULTA FILTRAR PERSONAS QUE SU IDENTIFICACION
                    //PERTENEZCA A LA LISTA DE SECCIONES PERTENECIENTES A LAS SECCIONES SELECCIONADAS
                $seccionesParaBuscar = explode(',', $user->niveles);
                $seccionesParaBuscar = array_map('intval', $seccionesParaBuscar);

                break;
        }

        $puntos = domicilio::join('identificacions', 'identificacions.id', '=', 'domicilios.identificacion_id')
        ->when($user->nivel_acceso != 'TODO', function ($query) use ($seccionesParaBuscar) {
            $query->whereIn('identificacions.seccion_id', $seccionesParaBuscar);
        })
        ->where('latitud', '!=', null)
        ->get(['latitud', 'longitud']);

        $domicilioArray = [];

        foreach ($puntos as $punto) {
            $coordenadas = array($punto->latitud, $punto->longitud);
            $domicilioArray[] = $coordenadas;
        }

        return view('mapa', compact('domicilioArray'));
    }
}
