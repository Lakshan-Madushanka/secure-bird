<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<Message>
 */
class MessageFactory extends Factory
{
    protected $model = Message::class;

    private string $userTimeZone = 'utc';

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'text' => $this->faker->paragraphs(random_int(3, 5), true),
            'password' => Hash::make('password'),
            'no_of_allowed_visits' => mt_rand(5, 10),
            'expires_at' => now()->addMinutes(5),
        ];
    }

    public function withTimeZone(string $timeZone): static
    {
        return $this->state(
            function (array $attributes) use ($timeZone) {
                return [
                    'userTimeZone' => $timeZone,
                    'expires_at' => now($timeZone)->addMinutes(5),
                ];
            }
        );
    }


}
