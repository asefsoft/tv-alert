
@php /* @type App\Models\TVShow $tvShow */ @endphp
<div>
    <x-dropdown width="" align="left">
        <x-slot name="trigger">
            <span class="inline-flex rounded-md items-center">
                {{-- Search Form --}}
                <form action="/search" class="w-60 xs:w-80">
                    <label for="search" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </div>
                        <input wire:model.live.debounce.800ms="term" name="term" type="search" id="search" class="block w-full p-3 pl-10 text-sm text-gray-900 border border-gray-300 rounded-full bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Search" required>
                        @if($usedFuzzy && isAdmin())
                        <span class="absolute right-16 bottom-3 text-sm text-gray-400">Fuz</span>
                        @endif

                        <button type="submit" class="text-white absolute right-1 top-1 bg-blue-700 hover:bg-blue-800 focus:ring-4
                            focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm p-2">
                            GO
                        </button>
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
                    <li class="flex justify-between space-y-2 px-3" wire:key="show-{{$tvShow->id}}">
                        <div class="flex flex-1 items-center hover:bg-gray-200 space-x-4 cursor-pointer pr-1 sm:pr-3" x-on:click.prevent="tvShowClicked($wire, {{$tvShow->id}})">
                            @if(!empty($tvShow->thumb_url))
                            {{-- Thumbnail --}}
                            <div class="flex-shrink-0">
                                <img class="w-8 h-9 text-xs rounded-md" src="{{ $tvShow->thumb_url }}" alt="No Poster">
                            </div>
                            @endif
                            {{-- Name --}}
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {!! strLimitHighlighted($tvShow->name, 35) !!}
                                </p>
                                {{-- Date, Country ... --}}
                                <p class="flex space-x-2 text-sm text-gray-500 truncate">
                                    <span>{{ $tvShow->start_date?->format('Y') }},</span>
                                    <span>{!! strLimitHighlighted($tvShow->network, 20, '') !!}, </span>
                                    <span>{{ $tvShow->country }}</span>
                                </p>
                            </div>
                            <div class="hidden sm:inline-flex items-center text-xs text-gray-800">
                                {{ $tvShow->status }}
                            </div>
                        </div>
                        {{-- Subscribe button --}}
                        <div class="hidden sm:inline-flex items-center text-xs text-gray-800 ml-2 pb-2">
                            <livewire:subscribe-button :tv-show="$tvShow" :showLoadingIndicator="false" wire:key="{{$tvShow->id}}"/>
                        </div>
                    </li>
                @endforeach
                    {{-- More results ... --}}
                    @if($possibleResults > 7)
                    <li class="text-center group flex justify-center">
                        <a href="/search?term={{$term}}" class="w-full text-sm p-2 font-semibold group-hover:text-m-red">
                            See More Results
                            <span class="text-sm text-gray-400 ml-1">({{ $possibleResults }})</span>
                        </a>
                    </li>
                    @endif
                </ul>

            @elseif(!empty($term))
                <div class="w-full px-4 py-2">No results!</div>
            @endif
        </x-slot>
    </x-dropdown>
    <style>
        hl {
            background-color: yellow;
        }
    </style>
</div>

<script>


</script>

