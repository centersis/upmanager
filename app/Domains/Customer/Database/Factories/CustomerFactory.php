<?php

namespace App\Domains\Customer\Database\Factories;

use App\Domains\Customer\Entities\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'status' => 'active',
        ];
    }
} 