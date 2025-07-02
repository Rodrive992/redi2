<?php

namespace Database\Factories;

use App\Models\Mesa;
use Illuminate\Database\Eloquent\Factories\Factory;

class MesaFactory extends Factory
{
    protected $model = Mesa::class;

    public function definition()
    {
        return [
            'usuario' => $this->faker->name(),
            'entrada' => $this->faker->sentence(),
            'nombre' => $this->faker->word(),
            'dependencia' => $this->faker->word(),
            'entregado_a' => $this->faker->name(),
            'estado' => 'Pendiente',
            'fecha' => $this->faker->dateTimeThisYear(),
        ];
    }
}