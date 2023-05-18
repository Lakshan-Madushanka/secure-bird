@props(['target' => ''])

<button
    type="button"
    {{ $attributes->merge(['class' => ' flex justify-between items-center space-x-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300
     font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700
     focus:outline-none dark:focus:ring-blue-800']) }}
>
    <span class="">{{$slot}} </span>

    @if ($target)
        <x-spinner wire:loading  wire:target="{{$target}}" class="!h-4 !w-4"/>
    @else
        <x-spinner wire:loading class="!h-4 !w-4"/>
    @endif
</button>
