<?php

namespace App\Imports;

use App\Models\localidad;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class localidadImport implements ToModel
{
    //LLAMAR CON DE LOCALIDADES
    public function model(array $row)
    {
        $localidad = localidad::find($row[0])->first();
        if(!isset($localidad)){
        return new localidad([
            'id' => $row[0],
            'nombre' => $row[8],
            'tipo' => $row[8],
            'seccion_id' => $row[5],
        ]);
        }
        else{
            return null;
        }
    }
}
