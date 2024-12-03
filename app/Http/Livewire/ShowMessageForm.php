<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Rules\MessageRule;
use LivewireUI\Modal\ModalComponent;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class ShowMessageForm extends ModalComponent
{
    public string $messageId;

    public function rules(): array
    {
        return [
            'messageId' => ['required', new MessageRule()]
        ];
    }

    public function show(): void
    {
        $this->validate();

        $this->redirect(route('messages.show', ['messageId' => $this->messageId]));

        $this->close();
    }

    public function close(): void
    {
        $this->closeModal();
    }

    public function render(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        return view('livewire.show-message-form');
    }
}
