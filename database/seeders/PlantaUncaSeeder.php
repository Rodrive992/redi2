<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PlantaUnca;

class PlantaUncaSeeder extends Seeder
{
    public function run()
    {
        // Generar 50 registros de PlantaUnca
        PlantaUnca::factory()->count(50)->create();
    }
}
