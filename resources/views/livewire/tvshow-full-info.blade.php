<div x-data="{isLoading: @entangle('isLoadingShowInfo')}">
    <div class="max-w-4xl mx-auto pb-4 bg-white rounded-lg shadow-md overflow-hidden">

        {{-- Loading Progress --}}
        <div class="lg:flex" x-show="isLoading">
            <div class="mb-4">
                <h3 class="text-xl font-semibold">Loading...</h3>
            </div>
        </div>

        {{-- Show Info --}}
        <div class="lg:flex" x-show="!isLoading">
            <!-- Left Column: Show Poster -->
            <div class="lg:w-1/2">
                <img src="{{ $tvShow?->image_url }}" alt="TV Show Poster" class="w-full h-auto">
            </div>

            <!-- Right Column: Show Information -->
            <div class="lg:w-1/2 p-4 pt-0 pl-2 sm:pl-0 lg:pl-4">
                <!-- Above Section: Name and Description -->
                <div class="mb-4 mt-2 lg:mt-0">
                    <h2 class="text-2xl font-semibold">{{ $tvShow?->name ?? 'Loading...'}}</h2>
                    <p class="text-gray-600 mt-2 text-justify">
                        {{ $tvShow?->getShowDescription($isModalMode ? 400 : 0) }}
                        @if($isModalMode)
                             <a class="font-semibold text-blue-600" href="{{$tvShow?->getFullInfoUrl()}}">Read More</a>
                        @endif
                    </p>
                </div>

                <!-- Below Section: Other Information -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="font-semibold">Genres:</p>
                        <p>{{ implode(', ', $tvShow?->genres ?? []) }}</p>
                    </div>
                    <div>
                        <p class="font-semibold">Status:</p>
                        <p>{{ $tvShow?->status }}</p>
                    </div>

                    <div>
                        <p class="font-semibold">Country:</p>
                        <p>{{ $tvShow?->country }}</p>
                    </div>
                    <div>
                        <p class="font-semibold">Network:</p>
                        <p>{{ $tvShow?->network }}</p>
                    </div>
                    <div>
                        <p class="font-semibold">Start Date:</p>
                        <p>{{ $tvShow?->start_date?->format('Y/m/d') }}</p>
                    </div>
                    @if($tvShow?->hasNexEpDate())
                        <div>
                            <p class="font-semibold">Next Episode:</p>
                            <p>{{ $tvShow?->getNextEpisodeDateText() }}</p>
                        </div>
                    @endif
                </div>
                {{-- Subscribe button --}}
                @if($tvShow)
                    <div class="mt-6 pb-2">
                        <livewire:subscribe-button :tv-show="$tvShow" class="w-full" wire:key="{{$tvShow?->id}}"/>
                    </div>
                @endif
            </div>
        </div>


    </div>

</div>
