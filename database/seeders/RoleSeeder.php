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
        $rolSU = Role::create(['name' => 'SUPER ADMINISTRADOR']);
        //ACCESO A TODO Y ESTADISTICOS
        $rolAdmin = Role::create(['name' => 'ADMINISTRADOR']);
        //CONTROL TOTAL DEL CRUD DE ENCUESTAS
        $rolSupervisor = Role::create(['name' => 'SUPERVISOR']);
        //CAPTURA Y EDICION DE ENCUESTAS
        $rolCapturista = Role::create(['name' => 'CAPTURISTA']);
        //Consulta registros
        $rolConsultas = Role::create(['name' => 'CONSULTAS']);

        //INTENTAR HACER UN PERMISO POR CADA RUTA
        Permission::create(['name' => 'crudUsuarios.index'])->syncRoles([$rolSU, $rolAdmin, $rolConsultas]);
        Permission::create(['name' => 'crudUsuarios.create'])->syncRoles([$rolSU, $rolAdmin]);
        Permission::create(['name' => 'crudUsuarios.edit'])->syncRoles([$rolSU, $rolAdmin]);
        Permission::create(['name' => 'crudUsuarios.delete'])->syncRoles([$rolSU, $rolAdmin]);

        Permission::create(['name' => 'crudSimpatizantes.index'])->syncRoles([$rolSU, $rolAdmin, $rolSupervisor, $rolCapturista, $rolConsultas]);
        Permission::create(['name' => 'crudSimpatizantes.verificar'])->syncRoles([$rolSU, $rolAdmin, $rolSupervisor]);
        Permission::create(['name' => 'crudSimpatizantes.modificar'])->syncRoles([$rolSU, $rolAdmin, $rolSupervisor, $rolCapturista]);
        Permission::create(['name' => 'crudSimpatizantes.consultar'])->syncRoles([$rolSU, $rolAdmin, $rolConsultas]);
        Permission::create(['name' => 'crudSimpatizantes.borrar'])->syncRoles([$rolSU, $rolAdmin, $rolSupervisor]);
        Permission::create(['name' => 'crudSimpatizantes.exportar'])->syncRoles([$rolSU, $rolAdmin]);

        Permission::create(['name' => 'agregarSimpatizante.index'])->syncRoles([$rolSU, $rolAdmin, $rolSupervisor, $rolCapturista]);

        Permission::create(['name' => 'encuestas.index'])->syncRoles([$rolSU, $rolAdmin, $rolCapturista]);
        Permission::create(['name' => 'encuestas.agregar'])->syncRoles([$rolSU, $rolAdmin]);
        Permission::create(['name' => 'encuestas.modificar'])->syncRoles([$rolSU, $rolAdmin]);
        Permission::create(['name' => 'encuestas.editar'])->syncRoles([$rolSU, $rolAdmin]);
        Permission::create(['name' => 'encuestas.capturar'])->syncRoles([$rolSU, $rolAdmin, $rolCapturista]);

        Permission::create(['name' => 'bitacora.index'])->syncRoles([$rolSU, $rolAdmin, $rolConsultas]);
        Permission::create(['name' => 'estadistica.index'])->syncRoles([$rolSU, $rolAdmin, $rolSupervisor, $rolConsultas]);
        Permission::create(['name' => 'estadistica.cambiarMeta'])->syncRoles([$rolSU, $rolAdmin]);
        Permission::create(['name' => 'mapa.index'])->syncRoles([$rolSU, $rolAdmin, $rolSupervisor, $rolConsultas]);
    }
}
