<?php

namespace Modules\Account\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Account\App\Models\Account;

class AccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Account::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $accountable = $this->faker->randomElement(['User', 'Organization']);
        $accountable_type = $accountable::class;
        $accountable_id = $accountable::all()->random()->id;

        $manageable = $this->faker->randomElement(['User', 'Organization']);
        $manageable_type = $manageable::class;
        $manageable_id = $manageable::all()->reject(fn ($manager) => $manager->id == $accountable_id)->random()->id;

        dd($accountable_type, $accountable_id, $manageable_type, $manageable_id);

        return [
            'name' => $this->faker->randomElement(['Savings', 'Checking', 'Credit']),
            'amount' => $this->faker->randomFloat(2, 1, 10000),
            'currency' => $this->faker->randomElement(['USD', 'EUR', 'GBP', 'NGN']),
            'primary' => $this->faker->boolean(),
            'accountable_type' => $this->faker->randomElement(['App\Models\User', 'App\Models\Organization']),
            'accountable_id' => $this->faker->uuid(),
            'manageable_type' => $this->faker->randomElement(['App\Models\User', 'App\Models\Organization']),
            'manageable_id' => $this->faker->uuid(),
        ];
    }
}
