<div x-data="{isLoading: @entangle('isLoadingShowInfo')}">
    <div class="max-w-4xl mx-auto sm:pb-4 bg-white rounded-lg overflow-hidden">

        {{-- Loading Progress --}}
        <div class="md:flex" x-show="isLoading">
            <div class="mb-4">
                <h3 class="text-xl font-semibold">Loading...</h3>
            </div>
        </div>

        {{-- Show Info --}}
        <div class="md:flex" x-show="!isLoading">
            <!-- Left Column: Show Poster -->
            <div class="flex justify-center md:w-1/2">
                <img src="{{ $tvShow?->image_url }}" alt="TV Show Poster" class="w-full h-auto max-w-xs md:max-w-sm object-cover hover:object-contain">
            </div>

            <!-- Right Column: Show Information -->
            <div class="md:w-1/2 p-4 pt-0 {{$isModalMode ? "pl-2 sm:pl-0 md:pl-4" : ""}}">
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
                        <p>{{ $tvShow?->getGenresText(6) }}</p>
                    </div>
                    <div>
                        <p class="font-semibold">Status:</p>
                        <p>{{ $tvShow?->status }}</p>
                    </div>

                    <div>
                        <p class="font-semibold">Network:</p>
                        <p>{{ $tvShow?->network }}</p>
                    </div>
                    <div>
                        <p class="font-semibold">Country:</p>
                        <p>{{ $tvShow?->country }}</p>
                    </div>
                    <div>
                        <p class="font-semibold">Start Date:</p>
                        <p>{{ $tvShow?->start_date?->format('Y/m/d') }}</p>
                    </div>
                    <div>
                        <p class="font-semibold">IMDb Info:</p>
                        <p>@include('livewire.partials.imdb-link')</p>
                    </div>

                    <div>
                        <p class="font-semibold">Next Episode:</p>
                        <p title="{{ $tvShow?->getNextEpisodeDateText('default', shouldBeFuture: true) }}">
                            {{ $tvShow?->getNextEpisodeDateText(shouldBeFuture: true) }}
                        </p>
                    </div>

                    <div>
                        <p class="font-semibold">Last Episode:</p>
                        <p title="{{ $tvShow?->getLastEpisodeDateText('default') }}">
                            {{ $tvShow?->getLastEpisodeDateText() }}
                        </p>
                        </div>
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
