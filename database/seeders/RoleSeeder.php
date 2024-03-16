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
        //ACCESO A TODO CON CANDADO PARA NO BORRAR
        $rolSU = Role::create(['name' => 'SUPER_ADMINISTRADOR']);
        //ACCESO A TODO Y ESTADISTICOS
        $rolAdmin = Role::create(['name' => 'ADMINISTRADOR']);
        //CONTROL TOTAL DEL CRUD DE ENCUESTAS
        $rolSupervisor = Role::create(['name' => 'SUPERVISOR']);
        //CAPTURA Y EDICION DE ENCUESTAS
        $rolCapturista = Role::create(['name' => 'CAPTURISTA']);

        //INTENTAR HACER UN PERMISO POR CADA RUTA
        Permission::create(['name' => 'crudUsuarios.index'])->syncRoles([$rolSU, $rolAdmin]);
        Permission::create(['name' => 'crudUsuarios.create'])->syncRoles([$rolSU, $rolAdmin]);
        Permission::create(['name' => 'crudUsuarios.edit'])->syncRoles([$rolSU, $rolAdmin]);
        Permission::create(['name' => 'crudUsuarios.delete'])->syncRoles([$rolSU, $rolAdmin]);

        Permission::create(['name' => 'controlUsuarios.index'])->syncRoles([$rolSU, $rolAdmin]);
        Permission::create(['name' => 'controlUsuarios.create'])->syncRoles([$rolSU, $rolAdmin]);
        Permission::create(['name' => 'controlUsuarios.edit'])->syncRoles([$rolSU, $rolAdmin]);
        Permission::create(['name' => 'controlUsuarios.delete'])->syncRoles([$rolSU, $rolAdmin]);

        Permission::create(['name' => 'tablero.index'])->syncRoles([$rolSU, $rolAdmin]);
        Permission::create(['name' => 'tablero.create'])->syncRoles([$rolSU, $rolAdmin]);
        Permission::create(['name' => 'tablero.edit'])->syncRoles([$rolSU, $rolAdmin]);
        Permission::create(['name' => 'tablero.delete'])->syncRoles([$rolSU, $rolAdmin]);

        Permission::create(['name' => 'capturarProspecto.index'])->syncRoles([$rolSU, $rolAdmin, $rolSupervisor, $rolCapturista]);
        Permission::create(['name' => 'capturarProspecto.create'])->syncRoles([$rolSU, $rolAdmin, $rolSupervisor, $rolCapturista]);
        Permission::create(['name' => 'capturarProspecto.edit'])->syncRoles([$rolSU, $rolAdmin, $rolSupervisor, $rolCapturista]);
        Permission::create(['name' => 'capturarProspecto.delete'])->syncRoles([$rolSU, $rolAdmin, $rolSupervisor]);

    }
}
