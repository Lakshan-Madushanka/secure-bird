@php use App\Enums\DecryptionStatus; @endphp

<div x-data @modal-closed.window="window.location.reload()">
    <div>
        @if($status === DecryptionStatus::Started->value)
            <p class="p-4 text-2xl font-bold">Your message is being decrypting ...</p>
        @endif
        @if($status === DecryptionStatus::Success->value)
            <p class="p-4 text-2xl font-bold">Decryption succeeded</p>
        @endif

        <div class="mt-4 mb-8 flex justify-center items-center">
            <div
                class="radial-progress bg-primary text-primary-content border-4 border-primary"
                style="--value:{{$percentage}}; --size: 8rem;">
                {{$percentage}}%
            </div>
        </div>

            @if($status === DecryptionStatus::Fail->value)
                <div class="ml-8 mt-8">
                    <p class="text-red-500 text-center font-bold text-2xl mb-4">Something went wrong please try again
                        later.</p>

                    <div class="flex justify-end">
                        <x-button wire:click="close">Close</x-button>
                    </div>
                </div>
            @endif
    </div>
</div>
