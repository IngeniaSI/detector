<?php

namespace App\Imports;

use App\Models\seccion;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;

class seccionImport implements ToModel
{
    //LLAMAR CON CATALOGO DE COLONIAS
    public function model(array $row)
    {
        $seccion = seccion::find($row[5]);
        if(!isset($seccion)){
        return new seccion([
            'id' => $row[5],
            'tipo' => $row[6],
            'distrito_local_id' => 1,
        ]);
        }
        else{
            return null;
        }
    }
}
