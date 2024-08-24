<?php

namespace Database\Factories;
// database/factories/TodoFactory.php

namespace Database\Factories;

use App\Models\Todo;
use Illuminate\Database\Eloquent\Factories\Factory;

class TodoFactory extends Factory
{
    protected $model = Todo::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'status' => $this->faker->randomElement(['Pending', 'in_progress', 'Done']),
        ];
    }
}
