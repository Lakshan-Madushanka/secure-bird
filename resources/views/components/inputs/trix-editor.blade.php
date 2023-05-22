@props(['text'])

<div wire:ignore x-data @trix-change="$wire.set('text', $refs.message.value)" {{$attributes->merge(['class' => ''])}}>

    <input x-cloak x-ref='message' id="trix-editor" value="{{$text}}" type="hidden" name="content">
    <trix-editor wire:ignore class="{{$attributes->get('trix-editor-class')}}" input="trix-editor" placeholder="Your secret message"></trix-editor>

    @push('scripts')
        <script type="text/javascript" src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>
    @endpush
</div>
