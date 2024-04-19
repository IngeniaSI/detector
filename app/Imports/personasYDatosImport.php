<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class personasYDatosImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $data = collect();

        foreach ($rows as $row) {
            if(isset($row[1])){
                $data->push([
                    'fechaRegistro' => $row[0],
                    'folio' => $row[1],
                    'nombres' => $row[2],
                    'apellidoPaterno' => $row[3],
                    'apellidoMaterno' => $row[4],
                    'genero' => $row[5],
                    'telefonoCelular' => $row[6],
                    'telefonoFijo' => $row[7],
                    'correo' => $row[8],
                    'claveElector' => $row[9],
                    'curp' => $row[10],
                    'seccion' => $row[11],
                    'fechaNacimiento' => $row[12],
                    'edadEstimada' => $row[13],
                    'calle' => $row[14],
                    'numeroExterior' => $row[15],
                    'numeroInterior' => $row[16],
                    'colonia' => $row[17],
                    'codigoPostal' => $row[18],
                    'rolEstructura' => $row[19],
                    'rolNumerico' => $row[20],
                    'curpPromotor' => $row[21],
                    'longitud' => $row[22],
                    'latitud' => $row[23],
                    'nombreFacebook' => $row[24],
                    'escolaridad' => $row[25],
                    'afiliado' => $row[26],
                    'simpatizante' => $row[27],
                    'programa' => $row[28],
                    'funcionCampanias' => $row[29],
                    'etiquetas' => $row[30],
                    'observaciones' => $row[31],
                ]);
            }
        }

        return $data;
    }
}
