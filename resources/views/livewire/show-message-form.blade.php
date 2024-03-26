<div>
    <x-slot:title>Show Message</x-slot:title>

    <div class="p-8">
        <x-inputs.label for="message_id">Enter Message ID</x-inputs.label>
        <x-inputs.input wire:model="messageId" wire:keydown.enter="show" id="message_id" type="text" placeholder="Message ID"/>
        @error('messageId')<div class="text-red-500">{{$message}}</div>@enderror

        <div class="flex w-full justify-between">
            <x-button wire:click="close" target="close" class="mt-4">Cancel</x-button>
            <x-button wire:click="show"  target="show" class="mt-4">Show</x-button>
        </div>
    </div>
</div>
