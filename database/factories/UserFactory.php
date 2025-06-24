<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    use HasFactory;
    // Definir el modelo asociado
    protected $model = User::class;

    // Definir cómo se crean los datos para el modelo
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'), // Puedes poner un valor estático o generar uno con Faker
            'cuil' => $this->faker->numerify('20##########'),  // Generar un CUIL falso
            'dependencia' => $this->faker->word,
            'desempenio' => $this->faker->word,
        ];
    }
}