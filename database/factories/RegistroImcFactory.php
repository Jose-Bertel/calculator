<?php

namespace Database\Factories;

use App\Models\RegistroImc;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegistroImcFactory extends Factory
{
    protected $model = RegistroImc::class;

    public function definition(): array
    {
        $estatura = $this->faker->randomFloat(2, 1.5, 1.9);
        $peso = $this->faker->numberBetween(50, 100);
        $imc = round($peso / ($estatura ** 2), 1);

        return [
            'user_id' => User::factory(),
            'peso' => $peso,
            'estatura' => $estatura,
            'imc' => $imc,
        ];
    }
}
