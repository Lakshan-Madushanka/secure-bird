@props(['field'])

@error($field . '*')
<p  {{$attributes->merge(['class' => '!mt-1 text-red-600 flex justify-start'])}}>{{$message}}</p>
@enderror
