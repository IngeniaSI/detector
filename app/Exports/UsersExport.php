<?php

namespace App\Exports;

use App\Models\colonia;
use App\Models\persona;
use App\Models\seccion;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $consulta = persona::join('identificacions', 'personas.id', '=', 'identificacions.persona_id')
        ->join('domicilios', 'identificacions.id', '=', 'domicilios.identificacion_id')
        ->where('personas.deleted_at', null)
        ->get([
            'personas.id',
            'fecha_registro',
            'personas.created_at as fecha_sistema',
            'folio',
            'personas.persona_id as promotor_id',
            'nombres',
            'apellido_paterno',
            'apellido_materno',
            'genero',
            'telefono_celular',
            'telefono_fijo',
            'correo',
            'nombre_en_facebook',
            'escolaridad',
            'afiliado',
            'simpatizante',
            'programa',
            'funcion_en_campania',
            'fecha_nacimiento',
            'edadPromedio',
            'observaciones',
            'etiquetas',
            'rolEstructura',
            'rolNumero',
            'supervisado',
            'clave_elector',
            'curp',
            'seccion_id',
            'calle',
            'numero_exterior',
            'numero_interior',
            'latitud',
            'longitud',
        ]);

        $array = [];
        foreach ($consulta as $row) {
            $seccion = (isset($row->seccion_id)) ? seccion::find($row->seccion_id) : null;
            $colonia = (isset($row->colonia_id)) ? colonia::find($row->colonia_id) : null;

            $aux = [
                'personas.id' => $row->id,
                'fecha_registro' => $row->fecha_registro,
                'personas.created_at as fecha_sistema' => $row->fecha_sistema,
                'folio' => $row->folio,
                'personas.persona_id as promotor_id' => $row->promotor_id,
                'nombres' => $row->nombres,
                'apellido_paterno' => $row->apellido_paterno,
                'apellido_materno' => $row->apellido_materno,
                'genero' => $row->genero,
                'fecha_nacimiento' => $row->fecha_nacimiento,
                'edadPromedio' => $row->edadPromedio,
                'telefono_celular' => $row->telefono_celular,
                'telefono_fijo' => $row->telefono_fijo,
                'correo' => $row->correo,
                'nombre_en_facebook' => $row->nombre_en_facebook,
                'escolaridad' => $row->escolaridad,
                'clave_elector' => $row->clave_elector,
                'curp' => $row->curp,
                'afiliado' => $row->afiliado,
                'simpatizante' => $row->simpatizante,
                'programa' => $row->programa,
                'funcion_en_campania' => $row->funcion_en_campania,
                'rolEstructura' => $row->rolEstructura,
                'rolNumero' => $row->rolNumero,
                'supervisado' => $row->supervisado,
                'calle' => $row->calle,
                'numero_exterior' => $row->numero_exterior,
                'numero_interior' => $row->numero_interior,
                'codigoPostal' => (isset($colonia)) ? $colonia->codigo_postal : '',
                'colonia' => (isset($colonia)) ? $colonia->nombre : '',
                'seccion_id' => $row->seccion_id,
                'distritoLocal' => (isset($seccion)) ? $seccion->distritoLocal->id : '',
                'municipio' => (isset($seccion)) ? $seccion->distritoLocal->municipio->nombre : '',
                'distritoFederal' => (isset($seccion)) ? $seccion->distritoLocal->municipio->distritoFederal->id : '',
                'entidad' => (isset($seccion)) ? $seccion->distritoLocal->municipio->distritoFederal->entidad->nombre : '',
                'latitud' => $row->latitud,
                'longitud' => $row->longitud,
                'observaciones' => $row->observaciones,
                'etiquetas' => $row->etiquetas,
            ];

            array_push($array, $aux);
        }
        return new Collection($array);

    }
    public function headings(): array{
        return [
            'Consecutivo',
            'Fecha del registro',
            'Fecha capturada en sistema',
            'Folio',
            'promotor_id',
            'Nombres',
            'Apellido paterno',
            'Apellido materno',
            'Genero',
            'Fecha de nacimiento',
            'Rango de edad',
            'Telefono celular',
            'Telefono fijo',
            'Correo',
            'Facebook',
            'Escolaridad',
            'Clave electoral',
            'CURP',
            'Afiliado',
            'Simpatizante',
            'Programa',
            'Función en campaña',
            'Rol Designado',
            'Id del rol',
            'Supervisado',
            'Calle',
            'Numero exterior',
            'Numero interior',
            'Código postal',
            'Colonia',
            'Sección',
            'Distrito Local',
            'Municipio',
            'Distrito Federal',
            'Entidad Federativa',

            'Latitud',
            'Longitud',
            'Observaciones',
            'Etiquetas',
        ];
    }
}
