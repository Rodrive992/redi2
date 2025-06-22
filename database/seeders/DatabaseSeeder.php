<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Llamar a los seeders
        $this->call([
            UsersTableSeeder::class,
            MesaSeeder::class,
            PlantaUncaSeeder::class,
            PlantaEduProvinciaSeeder::class,
            PlantaAdmProvinciaSeeder::class,
        ]);
    }
}