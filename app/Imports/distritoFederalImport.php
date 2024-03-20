<?php

namespace App\Imports;

use App\Models\distritoFederal;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class distritoFederalImport implements ToModel
{
    //LLAMAR CON 03_CATALOGO DE SECCIONES CON DISTRITOS ELECTORALES
    public function model(array $row)
    {
        $distritoFederalNoExiste = distritoFederal::find($row[2])->first();
        if(!isset($distritoFederalNoExiste)){
        return new distritoFederal([
            'id' => $row[2],
            'entidad_id' => $row[0],
        ]);
        }
        else{
            return null;
        }
    }
}
