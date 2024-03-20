<?php

namespace App\Imports;

use App\Models\colonia;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class coloniaImport implements ToModel
{
    //LLAMAR CON COLONIAS
    public function model(array $row)
    {
        $colonia = colonia::find($row[0])->first();
        if(!isset($colonia)){
        return new colonia([
            'id' => $row[0],
            'nombre' => $row[8],
            'tipo' => $row[7],
            'codigo_postal' => $row[9],
            'control' => $row[10],
            'seccion_id' => $row[5],
        ]);
        }
        else{
            return null;
        }
    }
}
