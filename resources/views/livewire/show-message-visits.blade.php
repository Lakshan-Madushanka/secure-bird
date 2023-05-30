<div class="overflow-x-auto p-2">
    <table x-data class="table w-full">
        <thead>
        <tr>
            <th>Ip Address</th>
            <th>Host</th>
            <th>Visited at</th>
        </tr>
        </thead>
        <tbody>
        @forelse($visits as $visit)
            <tr>
                <th>{{$visit->ip_address}}</th>
                <th>{{$visit->user_agent}}</th>
                <th x-text="new Date(@js($visit->created_at)).toLocaleString()"></th>
            </tr>
        @empty
            <tr>
                <th class="text-2xl font-bold">No visits found</th>
            </tr>
        @endforelse
        </tbody>
    </table>
    {{$visits->items()->links()}}
</div>
