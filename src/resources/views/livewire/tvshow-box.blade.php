<div x-data='{ tvshowId: {{$tvShow->id}} }'>
    <div class="flex flex-col max-w-[220px] h-full border  shadow-xl rounded-xl overflow-hidden
    transition-transform duration-200 hover:scale-105 hover:shadow-2xl {{ $tvShow->isEnded() ? 'border-gray-300 bg-neutral-200 hover:shadow-gray-300' : 'border-gray-200 bg-white' }}">
        <!-- TV Show Poster -->
        @if($displayPoster)
            <div class="flex flex-col relative">
                <a href="{{$tvShow->getFullInfoUrl()}}"
                   style="background-image: url('{{ $tvShow?->thumb_url }}');"
                   alt="TV Show Poster"
                   x-on:click.prevent="tvShowClicked($wire, tvshowId)"
                   class="w-full h-[220px] bg-contain bg-center transition-all duration-200 hover:brightness-90">
                </a>
                <div class="absolute top-2 right-2">
                    @include('livewire.partials.imdb-link')
                </div>
            </div>
        @endif

        <div class="px-3 py-2 flex flex-col flex-grow">
            <!-- TV Show Name -->
            <h2 class="text-base font-semibold mb-1  truncate {{ $tvShow->isEnded() ? 'text-gray-500' : 'text-gray-900' }}"
                title="{{ $tvShow->name }} ({{ $tvShow->getShowYearRange() }})">
                <a href="{{$tvShow->getFullInfoUrl()}}" x-on:click.prevent="tvShowClicked($wire, tvshowId)" class="hover:text-blue-600 transition-colors duration-150">{{ $tvShow->name }}</a>
                <span class="text-xs text-gray-500">({{ $tvShow->getShowYearRange() }})</span>
            </h2>

            <!-- Next Episode Date -->
            <p class="text-xs  mb-2 {{ $tvShow->isEnded() ? '' : 'text-blue-700' }}" title="Next Episode: {{$tvShow->getNextEpisodeDateText('default', shouldBeFuture: true)}}">
                <span class="font-medium">Next:</span> {{ $tvShow->getNextEpisodeDateText(shouldBeFuture: true) }}
            </p>

            <!-- Last Episode Date -->
            @if($displayLastEpDate)
                <p class="text-xs text-gray-700 mb-2" title="Last Episode: {{$tvShow->getLastEpisodeDateText('default')}}">
                    <span class="font-medium">Last:</span> {{ $tvShow->getLastEpisodeDateText() }}
                </p>
            @endif

            <!-- Status -->
            <div class="flex items-center text-xs text-gray-600 mb-2">
                <span class="inline-flex items-center py-1 font-semibold">Status: {{ $tvShow?->status }}</span>
            </div>

            <!-- Subscribe Button -->
            <livewire:subscribe-button :tv-show="$tvShow" wire:key="{{$tvShow->id}}"/>
        </div>
    </div>
</div>
