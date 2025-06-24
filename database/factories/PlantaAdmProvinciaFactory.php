<?php

namespace Database\Factories;

use App\Models\PlantaAdmProvincia;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlantaAdmProvinciaFactory extends Factory
{
    use HasFactory;
    // Definir el modelo asociado
    protected $model = PlantaAdmProvincia::class;

    // Definir cómo se crean los datos para el modelo
    public function definition()
    {
        return [
            'legajo' => $this->faker->unique()->numberBetween(1000, 9999),
            'nombre' => $this->faker->name,
            'dni' => $this->faker->numerify('###########'),
            'fecha_ingreso' => $this->faker->date,
            'dependencia' => $this->faker->word,
            'dependencia_comp' => $this->faker->word,
            'escalafon' => $this->faker->word,
            'agrupamiento' => $this->faker->word,
            'subrogancia' => $this->faker->word,
            'cargo' => $this->faker->word,
            'nro_cargo' => $this->faker->word,
            'caracter' => $this->faker->word,
            'dedicacion' => $this->faker->word,
            'alta_cargo' => $this->faker->date,
            'vencimiento_cargo' => $this->faker->date,
            'hs' => $this->faker->numberBetween(10, 40),  // Horas de trabajo
            'licencia' => $this->faker->word,
            'desempenio' => $this->faker->word,
            'estado_baja' => $this->faker->word,
            'puntaje' => $this->faker->numberBetween(1, 100),
            'alta_licencia' => $this->faker->date,
            'baja_licencia' => $this->faker->date,
        ];
    }
}