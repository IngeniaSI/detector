<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class personasMultipleExport implements WithMultipleSheets
{
    protected $fechaInicio;
    protected $fechaFin;

    public function __construct($fechaInicio, $fechaFin)
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
    }

    public function sheets(): array
    {
        return [
            'Listado Personas' => new listadoPersonasExport($this->fechaInicio, $this->fechaFin),
            'Promotores' => new promotoresExport(),
            'Colonias' => new coloniasExport(),
            'Rol Estructura' => new rolEstructuraExport(),
            'Genero' => new generoExport(),
            'Edad' => new edadExport(),
            'Escolaridad' => new escolaridadExport()
        ];
    }
}
