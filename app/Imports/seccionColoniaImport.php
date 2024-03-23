<?php

namespace App\Imports;

use App\Models\seccionColonia;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class seccionColoniaImport implements ToModel
{
    /**
    * @param Collection $collection
    */
    //LLAMAR CON COLONIAS
    public function model(array $row)
    {
        $pivote = seccionColonia::where('seccion_id', $row[5])->where('colonia_id', $row[0])->first();
        if(!isset($pivote)){
        return new seccionColonia([
            'seccion_id' => $row[5],
            'colonia_id' => $row[0],
        ]);
        }
        else{
            return null;
        }
    }
}
