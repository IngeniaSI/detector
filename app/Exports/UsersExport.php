<?php

namespace App\Exports;

use App\Models\persona;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return persona::join('identificacions', 'personas.id', '=', 'identificacions.persona_id')
        ->join('domicilios', 'identificacions.id', '=', 'domicilios.identificacion_id')
        ->where('personas.deleted_at', null)
        ->get([
            'personas.id as persona_id',
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
    }
    public function headings(): array{
        return [
            'persona_id',
            'fecha_registro',
            'fecha_sistema',
            'folio',
            'promotor_id',
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
        ];
    }
}
