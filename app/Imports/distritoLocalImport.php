<?php

namespace App\Imports;

use App\Models\distritoLocal;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class distritoLocalImport implements ToModel
{
    //LLAMAR CON 03_CATALOGO DE SECCIONES CON DISTRITOS ELECTORALES
    public function model(array $row)
    {
        $distritoLocal = distritoLocal::find($row[3]);
        if(!isset($distritoLocal)){
        return new distritoLocal([
            'id' => $row[3],
            'distrito_federal_id' => $row[2],
        ]);
        }
        else{
            return null;
        }
    }
}
