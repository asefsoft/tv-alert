<div x-data='{ tvshowId: {{$tvShow->id}} }'>
    <div class="max-w-[200px] h-full flex flex-col text-sm bg-white border border-gray-300 shadow-lg rounded-lg overflow-hidden">

        <!-- TV Show Poster -->
        @if($displayPoster)
            <a href="{{$tvShow->getFullInfoUrl()}}" style="background-image: url('{{ $tvShow?->thumb_url }}');" alt="TV Show Poster"
               x-on:click.prevent="tvShowClicked($wire, tvshowId)"
                 class="w-full h-[220px] bg-contain bg-center">
            </a>
        @endif

        <div class="px-2 py-2 flex flex-col flex-grow">
            <!-- TV Show Name -->
            <h2 class="text-base font-semibold mb-2">
                <a href="{{$tvShow->getFullInfoUrl()}}" x-on:click.prevent="tvShowClicked($wire, tvshowId)">{{ $tvShow->name }}</a>
            </h2>

            <!-- Next Episode Date -->
            <p class="text-gray-600 mb-3" title="Next Episode: {{$tvShow->getNextEpisodeDateText('default')}}">Next: {{ $tvShow->getNextEpisodeDateText() }}</p>

            <!-- Last Episode Date -->
            @if($displayLastEpDate)
                <p class="text-gray-600 mb-3" title="Last Episode: {{$tvShow->getLastEpisodeDateText('default')}}">Last: {{ $tvShow->getLastEpisodeDateText() }}</p>
            @endif

            <!-- Watch Later and Other Info -->
            <div class="flex justify-between flex-col flex-grow">

                <!-- Other Info -->
                <div class="flex justify-between flex-col text-gray-600 mb-3">
{{--                    <span>Rating: 9.5</span>--}}
                    <span>Status: {{ $tvShow?->status }}</span>
                </div>
            </div>

            <!-- Subscribe Button -->
            <livewire:subscribe-button :tv-show="$tvShow" wire:key="{{$tvShow->id}}"/>
        </div>
    </div>
</div>
