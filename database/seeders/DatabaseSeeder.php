<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        User::Create([
            'nombre' => 'EMILIO',
            'apellido_paterno' => 'MENDOZA',
            'apellido_materno' => 'SARMIENTO',
            'email' => 'emiliomendoza20@hotmail.com',
            'password' => Hash::make('123'),
            'email_verified_at' => Date("Y-m-d H:i:s"),
        ])->assignRole('SUPER_ADMINISTRADOR');
    }
}
