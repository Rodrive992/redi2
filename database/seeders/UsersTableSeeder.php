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
                'created_at' => now(),
                'updated_at' => now(),
            ],
             [
                'name' => 'Vale',
                'email' => 'vmonjes@unca.edu.ar',
                'password' => Hash::make('Unca123456'),
                'dependencia' => 'dgp',
                'desempenio' => 'mesa_entrada',
                'created_at' => now(),
                'updated_at' => now(),
            ],
             [
                'name' => 'Flavia',
                'email' => 'fchocobar@unca.edu.ar',
                'password' => Hash::make('Unca123456'),
                'dependencia' => 'dgp',
                'desempenio' => 'licencia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}