<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Imports\coloniaImport;
use App\Imports\distritoFederalImport;
use App\Imports\distritoLocalImport;
use App\Imports\entidadImport;
use App\Imports\metasSeccionImport;
use App\Imports\municipioImport;
use App\Imports\personasYDatosImport;
use App\Imports\seccionColoniaImport;
use App\Imports\seccionImport;
use App\Models\colonia;
use App\Models\distritoFederal;
use App\Models\distritoLocal;
use App\Models\domicilio;
use App\Models\entidad;
use App\Models\identificacion;
use App\Models\meta;
use App\Models\municipio;
use App\Models\persona;
use App\Models\seccion;
use App\Models\seccionColonia;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::unprepared(file_get_contents(storage_path('app/carga inicial detector.sql')));

        // $this->call(RoleSeeder::class);
        // $ivan = User::Create([
        //     'nombre' => 'IVAN',
        //     'apellido_paterno' => 'SOTO',
        //     'email' => 'ivan.soto@hotmail.com',
        //     'password' => Hash::make('123'),
        //     'email_verified_at' => Date("Y-m-d H:i:s"),
        //     'nivel_acceso' => 'ENTIDAD',
        // ]);
        // $ivan->assignRole('ADMINISTRADOR');

        // $belizario = User::Create([
        //     'nombre' => 'BELIZARIO',
        //     'apellido_paterno' => 'RUIZ',
        //     'email' => 'belizario.ruiz@hotmail.com',
        //     'password' => Hash::make('123'),
        //     'email_verified_at' => Date("Y-m-d H:i:s"),
        //     'nivel_acceso' => 'ENTIDAD',
        // ]);
        // $belizario->assignRole('SUPER ADMINISTRADOR');

        // $eduardo = User::Create([
        //     'nombre' => 'EDUARDO',
        //     'apellido_paterno' => 'REYES',
        //     'email' => 'eduardo.reyes@hotmail.com',
        //     'password' => Hash::make('123'),
        //     'email_verified_at' => Date("Y-m-d H:i:s"),
        //     'nivel_acceso' => 'ENTIDAD',
        // ]);
        // $eduardo->assignRole('SUPER ADMINISTRADOR');
        // $emilio = User::Create([
        //     'nombre' => 'EMILIO',
        //     'apellido_paterno' => 'MENDOZA',
        //     'email' => 'emiliomendoza20@hotmail.com',
        //     'password' => Hash::make('123'),
        //     'email_verified_at' => Date("Y-m-d H:i:s"),
        //     'nivel_acceso' => 'ENTIDAD',
        // ]);
        // $emilio->assignRole('SUPER ADMINISTRADOR');
        // User::Create([
        //     'nombre' => 'ANA',
        //     'apellido_paterno' => 'CECILIA',
        //     'email' => 'ana.cecilia@hotmail.com',
        //     'password' => Hash::make('123'),
        //     'email_verified_at' => Date("Y-m-d H:i:s"),
        //     'nivel_acceso' => 'ENTIDAD',
        // ])->assignRole('CAPTURISTA');

        // User::Create([
        //     'nombre' => 'HECTOR',
        //     'apellido_paterno' => 'GALVAN',
        //     'email' => 'hector.galvan@hotmail.com',
        //     'password' => Hash::make('123'),
        //     'email_verified_at' => Date("Y-m-d H:i:s"),
        //     'nivel_acceso' => 'ENTIDAD',
        // ])->assignRole('SUPERVISOR');

        // User::Create([
        //     'nombre' => 'Consultas',
        //     'apellido_paterno' => 'Nuevo',
        //     'email' => 'consultas.nuevo@hotmail.com',
        //     'password' => Hash::make('123'),
        //     'email_verified_at' => Date("Y-m-d H:i:s"),
        //     'nivel_acceso' => 'ENTIDAD',
        // ])->assignRole('CONSULTAS');



        // // CARGA INICIAL ENTIDAD
        // $dataForFirstTable = Excel::toArray(new entidadImport, storage_path('app/Catalogos/03_Cat+ílogo de Secciones con Distritos Electorales LOCALES.xlsx'));
        // foreach ($dataForFirstTable[0] as $row) {
        //     $entidadExiste = entidad::find($row[0]);
        //     if(isset($entidadExiste)){
        //         continue;
        //     }
        //     entidad::create([
        //         'id' => $row[0],
        //         'nombre' => $row[1],
        //     ]);
        // }

        // // CARGA INICIAL DISTRITO FEDERAL
        // $dataForFirstTable = Excel::toArray(new distritoFederalImport, storage_path('app/Catalogos/03_Cat+ílogo de Secciones con Distritos Electorales LOCALES.xlsx'));
        // foreach ($dataForFirstTable[0] as $row) {
        //     $entidadExiste = distritoFederal::find($row[2]);
        //     if(isset($entidadExiste)){
        //         continue;
        //     }
        //     distritoFederal::create([
        //         'id' => $row[2],
        //         'entidad_id' => $row[0],
        //     ]);
        // }

        // // CARGA INICIAL MUNICIPIO
        // $dataForFirstTable = Excel::toArray(new municipioImport, storage_path('app/Catalogos/03_Cat+ílogo de Secciones con Distritos Electorales LOCALES.xlsx'));
        // foreach ($dataForFirstTable[0] as $row) {
        //     $entidadExiste = municipio::find($row[4]);
        //     if(isset($entidadExiste)){
        //         continue;
        //     }
        //     municipio::create([
        //         'id' => $row[4],
        //         'nombre' => $row[5],
        //         'distrito_federal_id' => $row[2],
        //     ]);
        // }

        // // CARGA INICIAL DISTRITO LOCAL
        // $dataForFirstTable = Excel::toArray(new distritoLocalImport, storage_path('app/Catalogos/03_Cat+ílogo de Secciones con Distritos Electorales LOCALES.xlsx'));
        // foreach ($dataForFirstTable[0] as $row) {
        //     $entidadExiste = distritoLocal::find($row[3]);
        //     if(isset($entidadExiste)){
        //         continue;
        //     }
        //     distritoLocal::create([
        //         'id' => $row[3],
        //         'municipio_id' => $row[4],
        //     ]);
        // }

        // // CARGA INICIAL SECCION
        // $dataForFirstTable = Excel::toArray(new seccionImport, storage_path('app/Catalogos/03_Cat+ílogo de Secciones con Distritos Electorales LOCALES.xlsx'));
        // foreach ($dataForFirstTable[0] as $row) {
        //     if($row[6] == null){
        //         break;
        //     }
        //     $entidadExiste = seccion::find($row[6]);
        //     if(isset($entidadExiste)){
        //         continue;
        //     }
        //     seccion::create([
        //         'id' => $row[6],
        //         'distrito_local_id' => $row[3],
        //     ]);
        // }

        // // CARGA INICIAL SECCION
        // $dataForFirstTable = Excel::toArray(new seccionImport, storage_path('app/Catalogos/Catalogo de Colonias.xlsx'));
        // foreach ($dataForFirstTable[0] as $row) {
        //     if($row[5] == null){
        //         break;
        //     }
        //     $entidadExiste = seccion::find($row[5]);
        //     $entidadExiste->tipo = $row[6];
        //     $entidadExiste->save();
        // }

        // // CARGA INICIAL COLONIA
        // $dataForFirstTable = Excel::toArray(new coloniaImport, storage_path('app/Catalogos/Catalogo de Colonias.xlsx'));
        // foreach ($dataForFirstTable[0] as $row) {
        //     if($row[0] == null){
        //         break;
        //     }
        //     $entidadExiste = colonia::find($row[0]);
        //     if(isset($entidadExiste)){
        //         continue;
        //     }
        //     colonia::create([
        //         'id' => $row[0],
        //         'nombre' => $row[8],
        //         'tipo' => $row[7],
        //         'codigo_postal' => $row[9],
        //         'control' => $row[10],
        //     ]);
        // }

        // // CARGA INICIAL PIVOTE
        // $dataForFirstTable = Excel::toArray(new seccionColoniaImport, storage_path('app/Catalogos/Catalogo de Colonias.xlsx'));
        // foreach ($dataForFirstTable[0] as $row) {
        //     if($row[0] == null){
        //         break;
        //     }
        //     $entidadExiste = seccionColonia::where('seccion_id', $row[5])->where('colonia_id', $row[0])->first();
        //     if(isset($entidadExiste)){
        //         continue;
        //     }
        //     seccionColonia::create([
        //         'seccion_id' => $row[5],
        //         'colonia_id' => $row[0],
        //     ]);
        // }

        // // meta::create([
        // //     'numeroObjetivo' => 100,
        // //     'poblacionEstablecida' => 10000
        // // ]);


        // $datos = Excel::toCollection(new metasSeccionImport, storage_path('app/Catalogos/Listado Nominal Y META Secciones con Distritos Electorales PRUEBA.xlsx'));
        // foreach ($datos[0] as $dato) {
        //     if(!isset($dato[0])){
        //         break;
        //     }
        //     $seccion = seccion::find($dato[0]);
        //     $seccion->poblacion = $dato[1];
        //     $seccion->objetivo =  $dato[1]/2;
        //     $seccion->save();
        // }
        // $datos = Excel::toCollection(new personasYDatosImport, storage_path('app/Catalogos/FORMATO CARGA INICIAL OK.xlsx'));
        // foreach ($datos[0] as $dato) {
        //     if(!isset($dato[1])){
        //         break;
        //     }
        //     $nuevaPersona = new persona();
        //     if(isset($dato[0])){
        //         $numero_serie = $dato[0];
        //         echo $numero_serie."\n";
        //         $fecha = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($numero_serie);
        //         $nuevaPersona->fecha_registro = $fecha; // Salida: 2024-01-01;
        //         echo 'Fecha creacion:'.$fecha->format('Y-m-d')."\n";
        //     }
        //     $nuevaPersona->folio = $dato[1];
        //     $nuevaPersona->nombres = $dato[2];
        //     $nuevaPersona->apellido_paterno = $dato[3];
        //     $nuevaPersona->apellido_materno = $dato[4];
        //     $nuevaPersona->genero = ($dato[5] == 'M') ? 'HOMBRE' : 'MUJER';
        //     $nuevaPersona->telefono_celular = $dato[6];
        //     $nuevaPersona->telefono_fijo = $dato[7];
        //     $nuevaPersona->correo = $dato[8];
        //     $nuevaPersona->nombre_en_facebook = $dato[24];
        //     $nuevaPersona->escolaridad = $dato[25];
        //     $nuevaPersona->afiliado = $dato[27];
        //     $nuevaPersona->simpatizante = $dato[26];
        //     $nuevaPersona->programa = $dato[28];
        //     $nuevaPersona->funcion_en_campania = $dato[29];
        //     if(isset($dato[12])){
        //         $numero_serie = $dato[12];
        //         echo $numero_serie."\n";
        //         $fecha = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($numero_serie);
        //         $nuevaPersona->fecha_nacimiento =  $fecha; // Salida: 2024-01-01;
        //         echo 'Fecha nacimiento:'.$fecha->format('Y-m-d')."\n";
        //     }
        //     // $nuevaPersona->edadPromedio = $dato->; CALCULAR CON FECHA NACIMIENTO
        //     $nuevaPersona->observaciones = $dato[31];
        //     $nuevaPersona->etiquetas = $dato[30];
        //     $nuevaPersona->rolEstructura = $dato[19];
        //     $nuevaPersona->rolNumero = $dato[20];
        //     $nuevaPersona->save();

        //     $nuevaIdentificacion = new identificacion();
        //     $nuevaIdentificacion->clave_elector = $dato[9];
        //     $nuevaIdentificacion->curp = $dato[10];
        //     $nuevaIdentificacion->persona_id = $nuevaPersona->id;
        //     $nuevaIdentificacion->seccion_id = $dato[11];
        //     $nuevaIdentificacion->save();

        //     $domicilio = new domicilio();
        //     $domicilio->calle = $dato[14];
        //     $domicilio->numero_exterior = $dato[15];
        //     $domicilio->numero_interior = $dato[16];
        //     $idColonia = colonia::where('nombre', $dato[17])->first();
        //     if(isset($idColonia)){
        //         $domicilio->colonia_id = $idColonia->id;
        //     }
        //     $domicilio->latitud = $dato[22];
        //     $domicilio->longitud = $dato[23];
        //     $domicilio->identificacion_id = $nuevaIdentificacion->id;
        //     $domicilio->save();
        // }
    }
}
