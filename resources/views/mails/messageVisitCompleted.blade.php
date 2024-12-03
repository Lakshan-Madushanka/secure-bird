<x-mail::message>

# Your message visited Completed

## Message ID : *{{$message->getKey()}}*
- Created at: {{$message->created_at}}
- No of visits: {{$message->visits->count()}}


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
