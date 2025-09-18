<div x-data="{isLoading: @entangle('isLoadingShowInfo')}">
    <div class="max-w-5xl mx-auto  bg-white rounded-lg overflow-hidden shadow-lg {{$isModalMode ? "md:mb-1" : "lg:mb-6"}}">

        {{-- Loading Progress --}}
        <div class="md:flex justify-center items-center min-h-[200px]" x-show="isLoading">
            <div class="flex items-center space-x-3">
                <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-700">Loading...</h3>
            </div>
        </div>

        {{-- Show Info --}}
        <div class="md:flex flex-col" x-show="!isLoading">
            <!-- Left Column: Show Poster -->
            <div class="flex flex-col md:flex-row items-center md:items-start p-1 sm:p-6">
                <img src="{{ $tvShow?->image_url }}" alt="TV Show Poster"
                    class="w-full h-auto max-w-[18rem] max-h-96 object-cover md:mt-2 hover:object-contain rounded-lg shadow-md transition-all duration-300 hover:shadow-xl">

            <!-- Right Column: Show Information -->
            <div class="md:w-1/2 pt-0 {{$isModalMode ? "pl-2 sm:pl-0 md:pl-4" : "pl-2 sm:pl-5 "}}">
                <!-- Above Section: Name and Description -->
                <div class="mb-4 mt-2 md:mt-0 lg:mt-0">
                    <h2 class="text-2xl font-semibold"><a class="font-semibold text-blue-600" href="{{$tvShow?->getFullInfoUrl()}}">{{ $tvShow?->name ?? 'Loading...'}}</a>
                    <span class="text-xs text-gray-500">({{ $tvShow?->getShowYearRange() }})</span>
                    </h2>
                    <p class="text-gray-600 mt-2 text-justify">
                        {{ $tvShow?->getShowDescription($isModalMode ? 400 : 0) }}
                    </p>
                    @if($isModalMode)
                        <p class="mt-1">
                        <a class="font-semibold text-blue-600" href="{{$tvShow?->getFullInfoUrl()}}">Read More ...</a>
                        </p>
                    @endif
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
                        <p title="{{ $tvShow?->getLastEpisodeDateText('default', true) }}">
                            {{ $tvShow?->getLastEpisodeDateText(shouldBePast: true) }}
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

        <!-- Episodes Section -->
        @if(! $isModalMode)
            @php
                $seasons = $tvShow?->getGroupedEpisodesList();
            @endphp
        <div class="mt-8 px-3 sm:px-6 pb-6">
            <h3 class="text-2xl font-bold mb-4 text-gray-800 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1z"></path>
                </svg>
                Episodes
                <span class="text-sm font-medium text-gray-500 ml-2" title="took: {{ $seasons?->took }}ms">({{ number_format($seasons?->total_episodes) }} episodes in {{ $seasons?->count() }} seasons)</span>
            </h3>

            @if($seasons?->count())
                <div x-data="{ openSeason: 0 }" x-effect="if (!isLoading) openSeason = 0" class="space-y-4">
                    @foreach($seasons as $seasonNumber => $episodes)
                        <div class="border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200">
                            <!-- Season Header -->
                            <div
                                @click="openSeason = openSeason === {{ $seasonNumber }} ? null : {{ $seasonNumber }}"
                                class="bg-blue-100 px-4 sm:px-6 py-3 sm:py-4 cursor-pointer hover:bg-blue-200 transition-colors duration-200 flex justify-between items-center border-b border-blue-200">
                                <h4 class="text-base sm:text-lg font-semibold text-blue-900">Season {{ $seasonNumber }}
                                    <span class="text-xs text-gray-500">({{ $episodes->count() }} episodes)</span>
                                </h4>
                                <div class="flex items-center">
                                    <span class="text-blue-600 font-medium mr-3" title="Start Year">{{ $episodes->start_date->format('Y') }}</span>
                                <svg
                                    :class="{'rotate-180': openSeason === {{ $seasonNumber }}}"
                                    class="w-5 h-5 transition-transform duration-200"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                </div>
                            </div>

                            <!-- Episodes List -->
                            <div
                                x-show="openSeason === {{ $seasonNumber }}"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform -translate-y-2"
                                x-transition:enter-end="opacity-100 transform translate-y-0"
                                class="divide-y divide-gray-100">
                                @foreach($episodes as $episode)
                                    <div class="px-6 py-4 hover:bg-gray-50 transition-colors duration-150">
                                        <div class="flex justify-between items-center">
                                            <div class="flex items-center space-x-3">
                                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-md font-medium text-sm">E{{ str_pad($episode->episode, 2, '0', STR_PAD_LEFT) }}</span>
                                                <span class="font-medium text-gray-800">{{ $episode->name }}</span>
                                            </div>
                                            <div class="text-sm font-medium text-gray-500">
                                                {{ $episode->air_date->format('M d, Y') }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 italic">No episodes information available.</p>
            @endif
        </div>
        @endif

    </div>

</div>
