<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Rodri',
                'email' => 'rodrigo.vega@unca.edu.ar',
                'password' => Hash::make('Unca123456'),
                'dependencia' => 'dgp',
                'desempenio' => 'admin',
                'cuil' => '27271558665',
                'permiso' => 'editar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vale',
                'email' => 'vmonjes@unca.edu.ar',
                'password' => Hash::make('Unca123456'),
                'dependencia' => 'dgp',
                'desempenio' => 'mesa_entrada',
                'cuil' => '20345678901',
                'permiso' => 'autorizar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Flavia',
                'email' => 'fchocobar@unca.edu.ar',
                'password' => Hash::make('Unca123456'),
                'dependencia' => 'dgp',
                'desempenio' => 'licencia',
                'cuil' => '27123456789',
                'permiso' => 'editar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}