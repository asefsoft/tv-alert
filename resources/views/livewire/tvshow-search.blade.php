
@php /* @type App\Models\TVShow $tvShow */ @endphp
<div>
    <x-dropdown width="" align="left">
        <x-slot name="trigger">
            <span class="inline-flex rounded-md items-center">
                {{-- Search Form --}}
                <form action="/search" class="w-60 sm:w-80">
                    <label for="search" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </div>
                        <input wire:model.live.debounce.500ms="term" name="term" type="search" id="search" class="block w-full p-3 pl-10 text-sm text-gray-900 border border-gray-300 rounded-full bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Search" required>
                        @if($usedFuzzy)
                        <span class="absolute right-16 bottom-3 text-sm text-gray-400">Fuz</span>
                        @endif
                        <button type="submit" class="text-white absolute right-2 bottom-2 bg-blue-700 hover:bg-blue-800 focus:ring-4
                            focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-1">GO</button>
                    </div>
                </form>

                {{-- Loading indicator --}}
                <div class="ml-2 mb-1">
                    @include('livewire.partials.loading-indicator')
                </div>
            </span>
        </x-slot>

        {{-- Display results items --}}
        <x-slot name="content">
            @if(!empty($results) && count($results) > 0)
                <ul class="divide-y divide-gray-200" style="width: max-content">
                    @foreach($results as $i => $tvShow)
                    <li class="pb-1 sm:pb-2 hover:bg-gray-200 cursor-pointer px-3" x-on:click.prevent="tvShowClicked($wire, {{$tvShow->id}})">
                        <div class="flex items-center space-x-4">
                            @if(!empty($tvShow->thumb_url))
                            {{-- Thumbnail --}}
                            <div class="flex-shrink-0">
                                <img class="w-8 h-9 text-xs rounded-md" src="{{ $tvShow->thumb_url }}" alt="Poster">
                            </div>
                            @endif
                                {{-- Name --}}
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {{ Str::limit($tvShow->name, 35) }}
                                </p>
                                {{-- Date, Country ... --}}
                                <p class="flex space-x-2 text-sm text-gray-500 truncate">
                                    <span>{{ $tvShow->start_date?->format('Y') }}</span>
                                    <span>{{ Str::limit($tvShow->network, 15, '') }}</span>
                                    <span>{{ $tvShow->country }}</span>
                                </p>
                            </div>
                            <div class="inline-flex items-center text-xs text-gray-800">
                                {{ $tvShow->status }}
                            </div>
                        </div>
                    </li>
                @endforeach
                </ul>

            @elseif(!empty($term))
                <div class="w-full px-4 py-2">No results!</div>
            @endif
        </x-slot>
    </x-dropdown>
</div>

<script>
    // clicked on tv-show
    function tvShowClicked(wire, tvshowId) {
        wire.dispatch('tvshow-changed', [tvshowId]);
        // showing full info modal
        Livewire.getByName('modals.full-info-modal')[0].displayTvShowModal = true;
        // this will hide old data of current tv show
        Livewire.getByName('tv-show-full-info')[0].isLoadingShowInfo = true;
    }
</script>