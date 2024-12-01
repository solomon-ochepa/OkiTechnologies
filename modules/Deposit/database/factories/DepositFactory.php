<?php

namespace Modules\Deposit\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DepositFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Deposit\app\Models\Deposit::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }
}

