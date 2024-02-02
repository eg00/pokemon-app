<?php

namespace Database\Factories;

use App\Enums\Shape;
use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pokemon>
 */
class PokemonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'image' => $this->faker->imageUrl,
            'shape' => $this->faker->randomElement(Shape::cases()),
            'location_id' => Location::factory(),
        ];
    }
}
