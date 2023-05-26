<div>
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
</div>
