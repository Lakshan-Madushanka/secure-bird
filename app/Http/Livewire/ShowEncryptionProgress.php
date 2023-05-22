<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use LivewireUI\Modal\ModalComponent;

class ShowEncryptionProgress extends ModalComponent
{
    public string $messageId;
    public int $percentage = 0;

    public function mount(string $messageId): void
    {
        $this->messageId = $messageId;
    }

    /**
     * @return string[]
     */
    public function getListeners(): array
    {
        return [
            "echo:message.{$this->messageId},EncryptionSucceeded" => 'notifyEncryptionSucceeded',
            "echo:message.{$this->messageId},DataChunkEncrypted" => 'notifyDataChunkEncrypted',
        ];
    }

    /**
     * @param  array{id: string, percentage: int}  $data
     * @return void
     */
    public function notifyDataChunkEncrypted(array $data): void
    {
        $this->percentage = $data['percentage'];
    }

    public function notifyEncryptionSucceeded(): void
    {
        $this->percentage = 100;
    }

    public static function closeModalOnEscape(): bool
    {
        return false;
    }

    public static function closeModalOnClickAway(): bool
    {
        return false;
    }

    public function render(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        return view('livewire.show-encryption-progress');
    }
}
