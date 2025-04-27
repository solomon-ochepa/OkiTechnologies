<?php

namespace Modules\Domain\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Domain\App\Models\Domain;
use Modules\Tenant\App\Models\Tenant;

class DomainFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Domain::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'domain' => $this->faker->unique()->domainName(),
            'tenant_id' => Tenant::factory()->create()->id,
        ];
    }
}
