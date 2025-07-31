<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mesa;

class MesaSeeder extends Seeder
{
    public function run()
    {
        // Generar 50 registros de Mesa
        Mesa::factory()->count(50)->create();
    }
}