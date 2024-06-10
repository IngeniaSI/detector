<?php

namespace App\Exports;

use App\Models\oportunidad;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class oportunidadesExport implements FromCollection, WithHeadings
{
    protected $idPromotor;
    protected $estatus;

    public function __construct($idPromotor, $estatus)
    {
        $this->idPromotor = $idPromotor;
        $this->estatus = $estatus;
    }

    public function collection()
    {
        $actitivdades = DB::table('seguimientos as t1')
        ->select('t1.oportunidad_id', 't1.accion', 't1.observacion', 't1.fecha_registro', 't1.hora_registro', DB::raw('COUNT(t2.id) as total_actividades'))
        ->join('seguimientos as t2', 't1.oportunidad_id', '=', 't2.oportunidad_id')
        ->whereRaw('CONCAT(t1.fecha_registro, " ", t1.hora_registro) = (SELECT MAX(CONCAT(fecha_registro, " ", hora_registro)) FROM seguimientos WHERE oportunidad_id = t1.oportunidad_id)')
        ->groupBy('t1.oportunidad_id', 't1.accion', 't1.observacion', 't1.fecha_registro', 't1.hora_registro');

        $query = oportunidad::
        leftJoinSub($actitivdades, 'ultima_actividad', function ($join){
            $join->on('oportunidads.id', '=', 'ultima_actividad.oportunidad_id');
        })
        ->join('personas', 'personas.id', '=', 'oportunidads.persona_id')
        ->join('identificacions', 'identificacions.persona_id', '=', 'personas.id')
        ->join('domicilios', 'domicilios.identificacion_id', '=', 'identificacions.id')
        ->leftJoin('colonias', 'domicilios.colonia_id', '=', 'colonias.id')
        ->leftJoin('seccions', 'seccions.id', '=', 'identificacions.seccion_id')
        ->join('distrito_locals', 'seccions.distrito_local_id', '=', 'distrito_locals.id')
        ->join('municipios', 'municipios.id', '=', 'distrito_locals.municipio_id')
        ->select(
            'oportunidads.id',
            'personas.nombres',
            'personas.apellido_paterno',
            'personas.apellido_materno',
            'personas.telefono_celular',
            'personas.telefono_fijo',
            'calle',
            'numero_exterior',
            'numero_interior',
            'colonias.nombre as nombreColonia',
            'colonias.codigo_postal',
            'municipios.nombre as nombreMunicipio',
            'identificacions.seccion_id',
            'estatus',
            'ultima_actividad.total_actividades',
            'ultima_actividad.accion',
            'ultima_actividad.observacion',
            'ultima_actividad.fecha_registro',
            'ultima_actividad.hora_registro',
        );
        if ($this->idPromotor != 0 && $this->idPromotor != 'ALL') {
            $query->where('oportunidads.promotor_id', $this->idPromotor);
        }

        return $query->whereIn('oportunidads.estatus', $this->estatus)
        ->orderBy('oportunidads.created_at', 'DESC')
        ->get();

    }

    public function headings(): array
    {

        return [
            'Folio',
            'Nombres',
            'Apellido paterno',
            'Apellido materno',
            'Telefono celular',
            'Telefono fijo',
            'Calle',
            'Número exterior',
            'Número interior',
            'Colonia',
            'Código postal',
            'Municipio',
            'Sección',
            'Estatus',
            'Número de actividades realizadas',
            'Ultima actividad',
            'Observaciones de ultima actividad',
            'Fecha de ultima actividad',
            'Hora de ultima actividad',
        ];
    }
}
