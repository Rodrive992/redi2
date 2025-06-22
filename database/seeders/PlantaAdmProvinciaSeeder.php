<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PlantaAdmProvincia;

class PlantaAdmProvinciaSeeder extends Seeder
{
    public function run()
    {
        // Generar 50 registros de PlantaAdmProvincia
        PlantaAdmProvincia::factory()->count(50)->create();
    }
}