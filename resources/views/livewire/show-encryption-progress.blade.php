@php use App\Enums\EncryptionStatus; @endphp

<div x-data @modal-closed.window="window.location.reload()">
    <div>
        @if($status === EncryptionStatus::Started->value)
            <p class="p-4 text-2xl font-bold">Your message is being encrypting ...</p>
        @endif
        @if($status === EncryptionStatus::Success->value)
            <p class="p-4 text-2xl font-bold">Encryption Succeeded.</p>
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
                x-data="{showCopyStatusFor: '', setCopyStatus: function(value) {
                        this.showCopyStatusFor = value;
                        setTimeout(() => {this.showCopyStatusFor = ''}, 2000)
                        }}"
                x-init="new ClipboardJS('#copy-icon');"
                class="mx-8 mb-8 flex flex-col justify-center"
            >
                <div class="space-y-4">
                    <div class="flex flex-col">
                        <label class="mb-2" for="url">
                            Link
                        </label>
                        <div class="w-full flex items-center">
                            <x-inputs.input readonly id="url" class="w-full h-10 font-bold" value="{{$url}}"/>
                            <x-heroicon-o-clipboard
                                @click="setCopyStatus('url')"
                                id="copy-icon"
                                class="w-6 h-6 hover:cursor-pointer text-blue-600"
                                data-clipboard-target="#url"
                            />
                            <p x-show="showCopyStatusFor === 'url'" x-transition>copied</p>
                        </div>
                    </div>

                    <div class="flex flex-col">
                        <label class="mb-2" for="url">
                            Id
                        </label>
                        <div class="w-full flex items-center">
                            <x-inputs.input readonly id="Id" class="w-full h-10 font-bold" value="{{$messageId}}"/>
                            <x-heroicon-o-clipboard
                                @click="setCopyStatus('id')"
                                id="copy-icon"
                                class="w-6 h-6 hover:cursor-pointer text-blue-600"
                                data-clipboard-target="#Id"
                            />
                            <p x-show="showCopyStatusFor === 'id'" x-transition>copied</p>
                        </div>
                    </div>
                </div>
                <p class="text-red-600 mt-4">* Note that this is a one time generated data loosing it no way to regain !</p>
            </div>

            <div class="flex justify-end">
                <x-button wire:click="refreshPage">Close</x-button>
            </div>
        </div>
    @endif
</div>
