@php use App\Enums\DecryptionStatus; @endphp
<div class="min-h-screen">
    <x-slot:title>Decrypt your secrets</x-slot:title>

    @if($decryptionStatus !== DecryptionStatus::Success->value)
        <form
            wire:submit.prevent="show"
            class="flex flex-col justify-center items-center mt-8 mb-12 space-y-8"
        >
            <div wire:key="3" class="w-full sm:w-2/3">
                <x-inputs.label class="mb-4 text-lg" for="no_of_allowed_visits">
                    Enter the password associated with your message
                </x-inputs.label>
                <x-inputs.password wire:model.defer="password" id="password" placeholder="password"/>
                <x-inputs.input-error field="password"/>
            </div>

            <x-button wire:click="show" target="show">Show Message</x-button>
        </form>
    @endif

    @if($decryptionStatus ===  DecryptionStatus::Success->value)
        <div class="flex flex-col justify-center items-center space-y-4">
            <div class="w-full lg:w-2/3 bg-gray-200 collapse collapse-plus border border-base-300 bg-base-100 rounded-box">
                <input type="checkbox"/>
                <div class="collapse-title text-xl font-medium">
                    Click me to show/hide your message
                </div>
                <div class="collapse-content h-64 overflow-y-scroll bg-white">
                    <div class="p-4">{{$text}}</div>
                </div>
            </div>
            <div>
                <p>Medias</p>
            </div>
        </div>
    @endif
</div>
