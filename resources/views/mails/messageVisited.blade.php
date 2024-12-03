<x-mail::message>

# Your message was visited
## Message ID : *{{$message->getKey()}}*
<x-mail::table>
|IP Address                  | User Agent                      | At                        |
|----------------------------|---------------------------------|---------------------------|
@foreach($message->visits as $visit)
{{$visit->ip_address}}       |{{$visit->user_agent}}           |{{$visit->created_at}}     |
@endforeach
</x-mail::table>

Regards,<br/>
{{config('app.name')}}

</x-mail::message>
