<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Enums\EncryptionStatus;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use LivewireUI\Modal\ModalComponent;

class ShowEncryptionProgress extends ModalComponent
{
    public string $messageId;
    public int $percentage = 0;
    public string $status;

    public function mount(string $messageId): void
    {
        $this->messageId = $messageId;
        $this->status = EncryptionStatus::Started->value;
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
        $this->status = EncryptionStatus::Success->value;
    }

    public function refreshPage(): void
    {
        $this->dispatchBrowserEvent('modal-closed');
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
