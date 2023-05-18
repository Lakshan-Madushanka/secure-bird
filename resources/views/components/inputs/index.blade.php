@props(['field'])

@error($field)
<p class="!mt-1 lg:w-2/3 text-red-600 flex justify-start">{{$message}}</p>
@enderror
