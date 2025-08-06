<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Générer une date de début (starts_at) aléatoire dans l'année en cours
        $startsAt = $this->faker->dateTimeThisYear();

        // Créer une date de fin (ends_at) entre 1 et 5 heures après la date de début
        $endsAt = (clone $startsAt)->modify('+' . rand(1, 5) . ' hours');

        return [
            'title' => $this->faker->jobTitle(),
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
        ];
        }
}
