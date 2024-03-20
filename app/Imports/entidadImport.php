<?php

namespace App\Imports;

use App\Models\entidad;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class entidadImport implements ToModel
{
    //LLAMAR CON 03_CATALOGO DE SECCIONES CON DISTRITOS ELECTORALES
    public function model(array $row)
    {
        $entidadNoExiste = entidad::find($row[0])->first();
        if(!isset($entidadNoExiste)){
        return new entidad([
            'id' => $row[0],
            'nombre' => $row[1],
        ]);
        }
        else{
            return null;
        }
    }
}
