<?php

namespace App\Domains\Customer\Database\Factories;

use App\Domains\Customer\Entities\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
            'hash' => Str::uuid()->toString(),
            'status' => 'active',
        ];
    }
} 