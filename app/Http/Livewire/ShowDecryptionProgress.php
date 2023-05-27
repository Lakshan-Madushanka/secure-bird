<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Enums\DecryptionStatus;
use App\Enums\EncryptionStatus;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use LivewireUI\Modal\ModalComponent;

class ShowDecryptionProgress extends ModalComponent
{
    public string $messageId;
    public int $percentage = 0;
    public string $status;

    public function mount(string $messageId): void
    {
        $this->messageId = $messageId;
        $this->status = DecryptionStatus::Started->value;
    }

    /**
     * @return string[]
     */
    public function getListeners(): array
    {
        return [
            "echo:message.{$this->messageId},DataChunkDecrypted" => 'notifyDataChunkDecrypted',
            "echo:message.{$this->messageId},DecryptionSucceeded" => 'notifyDecryptionSucceeded',
            "echo:message.{$this->messageId},DecryptionFail" => 'notifyDecryptionFail',
        ];
    }

    /**
     * @param  array{id: string, percentage: int}  $data
     * @return void
     */
    public function notifyDataChunkDecrypted($data): void
    {
        $this->percentage = $data['percentage'];
    }

    /** @param  array{id: string, text: string}  $data */
    public function notifyDecryptionSucceeded(array $data): void
    {
        $this->percentage = 100;
        $this->status = EncryptionStatus::Success->value;

        $this->emitTo('show-message', 'decryptionSucceeded', $data);
        $this->close();
    }

    public function notifyDecryptionFail(): void
    {
        $this->status = DecryptionStatus::Fail->value;
    }

    public function close(): void
    {
        $this->closeModal();
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
        return view('livewire.show-decryption-progress');
    }
}
