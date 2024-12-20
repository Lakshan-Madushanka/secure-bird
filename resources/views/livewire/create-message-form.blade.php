<div
    x-data
    wire:init="$set('userTimeZone', Intl.DateTimeFormat().resolvedOptions().timeZone)"
>
    <x-slot:title>Encrypt your secrets</x-slot:title>

    <h1 class="flex justify-center text-center text-5xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-rose-400 via-fuchsia-500 to-indigo-500">
        Secure Bird
    </h1>

    <form
        x-show="! $wire.get('showSecurityForm')"
        wire:submit.prevent="validateDataForm()"
        class="my-12 flex flex-col justify-center items-center space-y-8"
    >
        <x-inputs.trix-editor wire:key="1" :text="$text" class="w-full lg:w-2/3" trix-editor-class="min-h-[20rem]"/>
        <x-inputs.input-error field="text" class="lg:w-2/3"/>


        <x-inputs.file-upload
            wire:key="2"
            wire:model="media" label="Upload your files max (25mb)"
            wrapperClass="w-full lg:w-2/3"
        />
        <x-inputs.input-error field="media" class="lg:w-2/3"/>

        <div class="w-full lg:w-2/3 flex justify-end">
            <x-button wire:click="next" target="next">
                Next
            </x-button>
        </div>
    </form>


    <form
        x-cloak
        x-show="$wire.get('showSecurityForm')"
        wire:submit.prevent="submit"
        class="flex flex-col justify-center items-center mt-8 mb-12 space-y-8"
    >
        <div wire:key="3" class="w-full sm:w-2/3">
            <x-inputs.label for="no_of_allowed_visits">
                Password
            </x-inputs.label>
            <x-inputs.password wire:model.defer="password" id="password"/>
            <x-inputs.input-error field="password"/>
        </div>

        <div class="w-full sm:w-2/3">
            <x-inputs.label for="no_of_allowed_visits">
                No of allowed visits
            </x-inputs.label>
            <x-inputs.input
                wire:model.defer="no_of_allowed_visits"
                id="no_of_allowed_visits"
                type="number"
                min="1" placeholder="No of allowed visits"
                required
                onkeydown="return event.keyCode !== 190"
            />
            <x-inputs.input-error field="no_of_allowed_visits"/>
        </div>

        <div class="w-full sm:w-2/3">
            <x-inputs.label for="expires_at">
                Expires at
            </x-inputs.label>
            <x-inputs.input
                wire:model.defer="expires_at"
                id="expires_at"
                type="datetime-local"
                placeholder="No of allowed visits"
                required
            />
            <x-inputs.input-error field="expires_at"/>
        </div>

        <div class="w-full sm:w-2/3">
            <x-inputs.label for="reference_mail">
                Reference mail
            </x-inputs.label>
            <div class="flex items-center relative">
                <x-inputs.input
                    wire:model.defer="reference_mail"
                    id="reference_mail"
                    type="email"
                    placeholder="Reference mail"
                    required
                />
                <div
                    class="tooltip tooltip-info tooltip-left absolute right-[-2rem]"
                    data-tip="We will send url to this email, so you don't need to wail until encryption process is finished
                              (This is helpful if your data is large"
                >
                    <x-heroicon-o-question-mark-circle class="w-6 h-6"/>
                </div>
            </div>
            <x-inputs.input-error field="reference_mail"/>
        </div>

        <div class="w-full sm:w-2/3 !mt-12 flex justify-between">
            <x-button wire:click="previous" target="previous">Previous</x-button>
            <x-button wire:click="submit" target="submit" class="bg-pink-500 hover:bg-pink-700">Get link</x-button>
        </div>
    </form>

    @push('styles')
        <style>
            trix-toolbar .trix-button-group--file-tools {
                display: none;
            }
        </style>

    @endpush
</div>


