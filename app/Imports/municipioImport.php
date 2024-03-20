<?php

namespace App\Imports;

use App\Models\municipio;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class municipioImport implements ToModel
{
    //LLAMAR CON 03_CATALOGO DE SECCIONES CON DISTRITOS ELECTORALES
    public function model(array $row)
    {
        $municipio = municipio::find($row[4])->first();
        if(!isset($municipio)){
        return new municipio([
            'id' => $row[4],
            'nombre' => $row[5],
            'distrito_local_id' => $row[3],
        ]);
        }
        else{
            return null;
        }
    }
}
