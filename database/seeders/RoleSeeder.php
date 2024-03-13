<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rolAdmin = Role::create(['name' => 'administrador']);
        $rolFiguraPublica = Role::create(['name' => 'figura_publica']);
        $rolCapturista = Role::create(['name' => 'capturista']);

        //INTENTAR HACER UN PERMISO POR CADA RUTA
        Permission::create(['name' => 'crudUsuarios.index'])->syncRoles([$rolAdmin]);
        Permission::create(['name' => 'crudUsuarios.create'])->syncRoles([$rolAdmin]);
        Permission::create(['name' => 'crudUsuarios.edit'])->syncRoles([$rolAdmin]);
        Permission::create(['name' => 'crudUsuarios.delete'])->syncRoles([$rolAdmin]);
        Permission::create(['name' => 'capturarProspecto.index'])->syncRoles([$rolAdmin, $rolFiguraPublica, $rolCapturista]);
        Permission::create(['name' => 'capturarProspecto.create'])->syncRoles([$rolAdmin, $rolFiguraPublica, $rolCapturista]);
        Permission::create(['name' => 'capturarProspecto.edit'])->syncRoles([$rolAdmin, $rolFiguraPublica, $rolCapturista]);
        Permission::create(['name' => 'capturarProspecto.delete'])->syncRoles([$rolAdmin, $rolFiguraPublica, $rolCapturista]);


    }
}
