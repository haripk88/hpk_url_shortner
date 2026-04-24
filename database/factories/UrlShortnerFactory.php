<?php

namespace Database\Factories;

use App\Models\UrlShortner;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Str;

/**
 * @extends Factory<UrlShortner>
 */
class UrlShortnerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'url' => $this->faker->url(),
            'company_id' => Company::factory(),
            'created_by' => User::factory(),
            'hits' => 0,
        ];
    }
}
