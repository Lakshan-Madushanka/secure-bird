<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Actions\ShowMessageAction;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Livewire\Component;
use RateLimiter;

class ShowMessage extends Component
{
    public string $messageId;
    public string $password;

    public function mount(string $messageId): void
    {
        $this->messageId = $messageId;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'password' => ['required'],
        ];
    }

    public function show(): void
    {
        $this->applyRateLimiter();
        $this->validate();

        app(ShowMessageAction::class)->execute($this->password, $this->messageId);
    }

    /**
     * @return void
     * @throws ThrottleRequestsException;
     */
    private function applyRateLimiter(): void
    {
        $key = request()->ip();

        if (is_null($key)) {
            return;
        }

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            $message = 'You may try again in '.$seconds.' seconds.';

            throw new ThrottleRequestsException($message);
        }

        RateLimiter::hit($key);
    }

    public function render(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        return view('livewire.show-message');
    }
}
