<div x-data='{ tvshowId: {{$tvShow->id}} }'>
    <div class="max-w-[200px] h-full flex flex-col text-sm bg-white border border-gray-300 shadow-lg rounded-lg overflow-hidden">

        <!-- TV Show Poster -->
        @if($displayPoster)
            <a href="{{$tvShow->getFullInfoUrl()}}" style="background-image: url('{{ $tvShow?->thumb_url }}');" alt="TV Show Poster"
               x-on:click.prevent="tvShowClicked($wire, tvshowId)"
                 class="w-full h-[220px]">
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
                <p class="text-gray-600 mb-3" title="Last Episode: {{$tvShow->getNextEpisodeDateText('default')}}">Last: {{ $tvShow->getLastEpisodeDateText() }}</p>
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

            <button class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600 {{ $isSubscribed ? 'bg-green-500 hover:bg-green-600' : 'bg-blue-500 hover:bg-blue-600' }}"
                    wire:loading.attr="disabled"
                    x-on:click="subscribeClicked($wire)"
            >
                {{ $isSubscribed ? 'Unsubscribe' : 'Subscribe' }}
                {{-- Loading indicator --}}
                <div class="absolute inline-block ml-3">
                @include('livewire.partials.loading-indicator')
                </div>
            </button>
        </div>
    </div>
</div>

<script>
    // clicked on subscribe
    function subscribeClicked(wire) {
        // not logged-in?
        if(window.isAuthenticated === '0') {
            // display register required modal form.
            Livewire.getByName('modals.registration-required')[0].displayRegisterModal = true;
        }
        else {
            // call subscribe on livewire component
            wire.call('subscribe');
        }
    }

    // clicked on link of tv-show
    function tvShowClicked(wire, tvshowId) {
        wire.dispatch('tvshow-changed', [tvshowId]);
        // showing full info modal
        Livewire.getByName('modals.full-info-modal')[0].displayTvShowModal = true;
        // this will hide old data of current tv show
        Livewire.getByName('tv-show-full-info')[0].isLoadingShowInfo = true;
    }

</script>
