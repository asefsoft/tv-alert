<x-mail::message>
# Today TV Shows

Hello dear {{ $user->name }}

New episodes of your subscribed TV shows are available today:

@foreach($todayShows as $show)
[{{ $show->name }} ({{ $show->start_date?->format("Y") }})]({{$show->getFullInfoUrl()}}) [{{ $show->ep_info }}]<br>
@endforeach

<x-mail::button :url="route('display-timeline')">
See Your Timeline
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}

 <span style="font-size: 13px; color: #909090">You can unsubscribe from this type of email on [timeline]({{route('display-timeline')}}) page.</span>
</x-mail::message>
