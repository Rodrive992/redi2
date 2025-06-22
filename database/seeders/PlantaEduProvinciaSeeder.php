<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PlantaEduProvincia;

class PlantaEduProvinciaSeeder extends Seeder
{
    public function run()
    {
        // Generar 50 registros de PlantaEduProvincia
        PlantaEduProvincia::factory()->count(50)->create();
    }
}
