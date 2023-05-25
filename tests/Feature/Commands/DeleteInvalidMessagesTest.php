<?php

declare(strict_types=1);


use App\Models\Message;
use Illuminate\Support\Facades\Artisan;

use function Pest\Laravel\assertDatabaseCount;

it('return message if no of visits not exceeded', function (): void {
    Message::factory()
        ->count(3)
        ->create(['expires_at' => now()->subMinute(5)]);

    Artisan::call('message:delete-invalid');

    assertDatabaseCount('messages', 0);
});
