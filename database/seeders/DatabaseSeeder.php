<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Imports\coloniaImport;
use App\Imports\distritoFederalImport;
use App\Imports\distritoLocalImport;
use App\Imports\entidadImport;
use App\Imports\municipioImport;
use App\Imports\seccionColoniaImport;
use App\Imports\seccionImport;
use App\Models\colonia;
use App\Models\distritoFederal;
use App\Models\distritoLocal;
use App\Models\entidad;
use App\Models\municipio;
use App\Models\seccion;
use App\Models\seccionColonia;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        $this->call(RoleSeeder::class);
        $ivan = User::Create([
            'nombre' => 'IVAN',
            'apellido_paterno' => 'SOTO',
            'email' => 'ivan.soto@hotmail.com',
            'password' => Hash::make('123'),
            'email_verified_at' => Date("Y-m-d H:i:s"),
            'nivel_acceso' => 'ENTIDAD',
        ]);
        $ivan->assignRole('ADMINISTRADOR');

        $belizario = User::Create([
            'nombre' => 'BELIZARIO',
            'apellido_paterno' => 'RUIZ',
            'email' => 'belizario.ruiz@hotmail.com',
            'password' => Hash::make('123'),
            'email_verified_at' => Date("Y-m-d H:i:s"),
            'nivel_acceso' => 'ENTIDAD',
        ]);
        $belizario->assignRole('SUPER ADMINISTRADOR');

        $eduardo = User::Create([
            'nombre' => 'EDUARDO',
            'apellido_paterno' => 'REYES',
            'email' => 'eduardo.reyes@hotmail.com',
            'password' => Hash::make('123'),
            'email_verified_at' => Date("Y-m-d H:i:s"),
            'nivel_acceso' => 'ENTIDAD',
        ]);
        $eduardo->assignRole('SUPER ADMINISTRADOR');
        $emilio = User::Create([
            'nombre' => 'EMILIO',
            'apellido_paterno' => 'MENDOZA',
            'email' => 'emiliomendoza20@hotmail.com',
            'password' => Hash::make('123'),
            'email_verified_at' => Date("Y-m-d H:i:s"),
            'nivel_acceso' => 'ENTIDAD',
        ]);
        $emilio->assignRole('SUPER ADMINISTRADOR');
        User::Create([
            'nombre' => 'ANA',
            'apellido_paterno' => 'CECILIA',
            'email' => 'ana.cecilia@hotmail.com',
            'password' => Hash::make('123'),
            'email_verified_at' => Date("Y-m-d H:i:s"),
            'nivel_acceso' => 'ENTIDAD',
        ])->assignRole('CAPTURISTA');

        User::Create([
            'nombre' => 'HECTOR',
            'apellido_paterno' => 'GALVAN',
            'email' => 'hector.galvan@hotmail.com',
            'password' => Hash::make('123'),
            'email_verified_at' => Date("Y-m-d H:i:s"),
            'nivel_acceso' => 'ENTIDAD',
        ])->assignRole('SUPERVISOR');


        // CARGA INICIAL ENTIDAD
        $dataForFirstTable = Excel::toArray(new entidadImport, storage_path('app/Catalogos/03_Cat+ílogo de Secciones con Distritos Electorales LOCALES.xlsx'));
        foreach ($dataForFirstTable[0] as $row) {
            $entidadExiste = entidad::find($row[0]);
            if(isset($entidadExiste)){
                continue;
            }
            entidad::create([
                'id' => $row[0],
                'nombre' => $row[1],
            ]);
        }

        // CARGA INICIAL DISTRITO FEDERAL
        $dataForFirstTable = Excel::toArray(new distritoFederalImport, storage_path('app/Catalogos/03_Cat+ílogo de Secciones con Distritos Electorales LOCALES.xlsx'));
        foreach ($dataForFirstTable[0] as $row) {
            $entidadExiste = distritoFederal::find($row[2]);
            if(isset($entidadExiste)){
                continue;
            }
            distritoFederal::create([
                'id' => $row[2],
                'entidad_id' => $row[0],
            ]);
        }

        // CARGA INICIAL MUNICIPIO
        $dataForFirstTable = Excel::toArray(new municipioImport, storage_path('app/Catalogos/03_Cat+ílogo de Secciones con Distritos Electorales LOCALES.xlsx'));
        foreach ($dataForFirstTable[0] as $row) {
            $entidadExiste = municipio::find($row[4]);
            if(isset($entidadExiste)){
                continue;
            }
            municipio::create([
                'id' => $row[4],
                'nombre' => $row[5],
                'distrito_federal_id' => $row[2],
            ]);
        }

        // CARGA INICIAL DISTRITO LOCAL
        $dataForFirstTable = Excel::toArray(new distritoLocalImport, storage_path('app/Catalogos/03_Cat+ílogo de Secciones con Distritos Electorales LOCALES.xlsx'));
        foreach ($dataForFirstTable[0] as $row) {
            $entidadExiste = distritoLocal::find($row[3]);
            if(isset($entidadExiste)){
                continue;
            }
            distritoLocal::create([
                'id' => $row[3],
                'municipio_id' => $row[4],
            ]);
        }

        // CARGA INICIAL SECCION
        $dataForFirstTable = Excel::toArray(new seccionImport, storage_path('app/Catalogos/Catalogo de Colonias.xlsx'));
        foreach ($dataForFirstTable[0] as $row) {
            if($row[5] == null){
                break;
            }
            $entidadExiste = seccion::find($row[5]);
            if(isset($entidadExiste)){
                continue;
            }
            seccion::create([
                'id' => $row[5],
                'tipo' => $row[6],
                'distrito_local_id' => 1,
            ]);
        }

        // CARGA INICIAL COLONIA
        $dataForFirstTable = Excel::toArray(new coloniaImport, storage_path('app/Catalogos/Catalogo de Colonias.xlsx'));
        foreach ($dataForFirstTable[0] as $row) {
            if($row[0] == null){
                break;
            }
            $entidadExiste = colonia::find($row[0]);
            if(isset($entidadExiste)){
                continue;
            }
            colonia::create([
                'id' => $row[0],
                'nombre' => $row[8],
                'tipo' => $row[7],
                'codigo_postal' => $row[9],
                'control' => $row[10],
            ]);
        }

        // CARGA INICIAL PIVOTE
        $dataForFirstTable = Excel::toArray(new seccionColoniaImport, storage_path('app/Catalogos/Catalogo de Colonias.xlsx'));
        foreach ($dataForFirstTable[0] as $row) {
            if($row[0] == null){
                break;
            }
            $entidadExiste = seccionColonia::where('seccion_id', $row[5])->where('colonia_id', $row[0])->first();
            if(isset($entidadExiste)){
                continue;
            }
            seccionColonia::create([
                'seccion_id' => $row[5],
                'colonia_id' => $row[0],
            ]);
        }
    }
}
