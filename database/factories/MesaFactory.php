<?php

namespace Database\Factories;

use App\Models\Mesa;
use Illuminate\Database\Eloquent\Factories\Factory;

class MesaFactory extends Factory
{
    use HasFactory;
    // Definir el modelo asociado
    protected $model = Mesa::class;

    // Definir cómo se crean los datos para el modelo
    public function definition()
    {
        return [
            'usuario' => $this->faker->name,
            'entrada' => $this->faker->word,
            'nombre' => $this->faker->word,
            'dependencia' => $this->faker->word,
            'entregado_a' => $this->faker->name,
            'estado' => $this->faker->randomElement(['Pendiente', 'Completado', 'En Proceso']),
            'fecha' => $this->faker->date('Y-m-d', 'now'),
        ];
    }
}