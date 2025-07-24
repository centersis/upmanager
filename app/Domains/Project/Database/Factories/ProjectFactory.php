<?php

namespace App\Domains\Project\Database\Factories;

use App\Domains\Project\Entities\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'status' => 'active',
            'hash' => Str::uuid()->toString(),
        ];
    }
} 