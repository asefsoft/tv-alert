
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
                        <button type="submit" class="text-white absolute right-2 bottom-2 bg-blue-700 hover:bg-blue-800 focus:ring-4
                            focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-1">GO</button>
                    </div>
                </form>

                {{-- Loading indicator --}}
                <div wire:loading role="status" class="flex justify-center">
                    <svg aria-hidden="true" class="w-6 h-6 ml-2 text-gray-200 animate-spin fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/><path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/></svg>
                    <span class="sr-only">Loading...</span>
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
