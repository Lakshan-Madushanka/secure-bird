<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Rules\UploadedMediaSizeRule;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class MessageForm extends Component
{
    use WithFileUploads;

    public string $text = "";
    public string $password = '';
    public int|string $no_of_allowed_visits = '';
    public string $expires_at = '';
    /** @var TemporaryUploadedFile[] */
    public $media = [];

    public string $uuid = '';
    public string $userTimeZone = 'utc';
    public bool $showSecurityForm = false;

    public int $maxAllowedUploadSize;

    public function mount(): void
    {
        /** @var string $maxAllowedUploadSize */
        $maxAllowedUploadSize = config('secure-bird.max_media_upload_size');
        $this->maxAllowedUploadSize = (int) $maxAllowedUploadSize;
    }

    /** @return array<string, array<int, object|string|null>> */
    protected function rules(): array
    {
        return [...$this->getMessageFormRules(), ...$this->getSecurityFormRules()];
    }

    /** @return array<string, array<int, string|object>> */
    private function getMessageFormRules(): array
    {
        return [
            'text' => ['required'],
            'media' => ['array', new UploadedMediaSizeRule($this->maxAllowedUploadSize)],
            'media.*' => ['file'],
        ];
    }

    /** @return array<string, array<int, Password|string|null>> */
    private function getSecurityFormRules(): array
    {
        return [
            'password' => ['required', Password::defaults()],
            'no_of_allowed_visits' => ['integer', 'min:1'],
            'expires_at' => ['date', 'after:'.now($this->userTimeZone)->toDateTimeString()],
        ];
    }

    public function next(): void
    {
        $this->validateMessageForm();
        $this->showSecurityForm = true;
    }

    public function previous(): void
    {
        $this->showSecurityForm = false;
    }

    public function validateMessageForm(): void
    {
        $this->validate([...$this->getMessageFormRules()]);
    }

    public function submit(): void
    {
        $this->validate();

        dump($this->all());
    }

    public function render(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        return view('livewire.message-form');
    }
}
