<?php

namespace App\Imports;

use App\Models\seccion;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class metasSeccionImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $data = collect();

        foreach ($rows as $row) {
            $data->push([
                'idSeccion' => $row[0],
                'poblacion' => $row[1],
                'objetivo' => $row[2],
            ]);
        }

        return $data;
    }
}
