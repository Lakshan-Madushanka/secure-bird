<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Actions\DownloadMediaAction;
use App\Actions\ShowMessageAction;
use App\Enums\DecryptionStatus;
use App\Models\Message;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use RateLimiter;

class ShowMessage extends Component
{
    public string $messageId;
    public string $password;
    public string $text = '';
    public bool $haveMedia = false;
    public string $decryptionStatus = '';

    public function mount(string $messageId): void
    {
        $this->messageId = $messageId;
    }

    /** @var string[] */
    protected $listeners = ['decryptionSucceeded' => 'showContent'];

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
        $this->decryptionStatus = DecryptionStatus::Started->value;

        $this->showProgressModal($this->messageId);
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

    public function showProgressModal(string $messageId): void
    {
        $this->emit('openModal', 'show-decryption-progress', ['messageId' => $messageId]);
    }

    public function showVisitsModal(): void
    {
        $this->emit('openModal', 'show-message-visits', ['messageId' => $this->messageId]);
    }

    /**
     * @param  array{id: string, text: string}  $data
     * @return void
     */
    public function showContent(array $data): void
    {
        $this->decryptionStatus = DecryptionStatus::Success->value;
        $this->text = $data['text'];
        $this->haveMedia = $this->mediaExists();
    }

    /*public function getVisitsCountProperty(): int
    {
        return Message::findOrFail($this->messageId)->visits()->count();
    }*/

    public function removeDecryptedMedia(): void
    {
        $msg = Message::findOrFail($this->messageId);

        $path = $msg->decryptedMediaStoragePath;

        if (Storage::exists($path)) {
            Storage::deleteDirectory($path);
        }
    }

    public function mediaExists(): bool
    {
        return app(DownloadMediaAction::class)->mediaExists($this->messageId);
    }

    public function render(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        return view('livewire.show-message');
    }
}
