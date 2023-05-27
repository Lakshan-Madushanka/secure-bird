@php use App\Enums\EncryptionStatus; @endphp

<div x-data @modal-closed.window="window.location.reload()">
    <div>
        @if($status === EncryptionStatus::Started->value)
            <p class="p-4 text-2xl font-bold">Your message is being encrypting ...</p>
        @endif
        @if($status === EncryptionStatus::Success->value)
            <p class="p-4 text-2xl font-bold">Encryption succeeded</p>
        @endif

        <div class="mt-4 mb-8 flex justify-center items-center">
            <div
                class="radial-progress bg-primary text-primary-content border-4 border-primary"
                style="--value:{{$percentage}}; --size: 8rem;">
                {{$percentage}}%
            </div>
        </div>
    </div>

    @if($status === EncryptionStatus::Success->value)
        <div>
            <div
                x-data
                x-init="new ClipboardJS('#copy-icon');"
                class="mx-8 mb-8 flex flex-col justify-center"
            >

                <label class="w-full mb-2" for="url">
                    Your link
                </label>

                <div
                    x-data="{showCopyStatus: false, setCopyStatus: function() {
                        this.showCopyStatus = true;
                        setTimeout(() => {this.showCopyStatus = false}, 5000)
                        }}"
                    class="w-full flex items-center"
                >
                    <x-inputs.input readonly id="url" class="w-full h-10 font-bold" value="{{$url}}"/>
                    <x-heroicon-o-clipboard
                        @click="setCopyStatus()"
                        id="copy-icon"
                        class="w-6 h-6 hover:cursor-pointer text-blue-600"
                        data-clipboard-target="#url"
                    />
                    <p x-show="showCopyStatus" x-transition>copied</p>
                </div>
                <p class="text-red-600 mt-2">* Note that this is a one time generated url loosing it no way to regain
                    !</p>
            </div>

            <div class="flex justify-end">
                <x-button wire:click="refreshPage">Close</x-button>
            </div>
        </div>
    @endif
</div>
