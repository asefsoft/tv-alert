<x-mail::message>
# Today TV Shows

Hello dear {{ $user->name }}

This is list of your subscribed tv shows with a new episode for today.

@foreach($todayShows as $show)
[{{ $show->name }} ({{ $show->start_date?->format("Y") }})]({{$show->getFullInfoUrl()}})<br>
@endforeach

<x-mail::button :url="route('display-timeline')">
See Your Timeline
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
