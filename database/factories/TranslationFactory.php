<?php

namespace Database\Factories;

use App\Models\Locale;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Translation>
 */
class TranslationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $locale = Locale::inRandomOrder()->first() ?? Locale::factory()->create(); 
        return [
            'key' => $this->faker->unique()->word(),
            'locale_id' => $locale->id,
            'value' => $this->faker->sentence(),
        ];
    }
}
