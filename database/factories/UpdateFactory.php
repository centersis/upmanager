<?php

namespace Database\Factories;

use App\Models\Update;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Update>
 */
class UpdateFactory extends Factory
{
    protected $model = Update::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'title' => $this->faker->sentence(4),
            'caption' => $this->faker->sentence(6),
            'description' => $this->faker->paragraph(),
            'views' => 0,
            'hash' => Str::uuid()->toString(),
            'status' => 'pending',
        ];
    }
} 