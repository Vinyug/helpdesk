<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'subject' => fake()->sentence(5),
            'uuid' => fake()->uuid(),
            'state' => fake()->sentence(1),
            'service' => 'Non lu',
            'visibility' => 1,
            'hourly_rate' => 20,
            'notification_sent' => 0,
            'editable' => 1,
        ];
    }
}
