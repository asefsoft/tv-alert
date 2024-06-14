<div>
    <div class="mx-auto px-4 py-8">
        {{-- Search Box --}}
        <div class="mb-6 flex">
            <label class="flex-1">
                <input wire:model.live.debounce.800ms="term" type="text" placeholder="Search TV Shows"
                       class="w-full border border-gray-300 p-2 rounded-md shadow-md">
            </label>
            {{-- Loading indicator --}}
            <div class="w-9 ml-2 mt-2 hidden xs:block">
                @include('livewire.partials.loading-indicator')
            </div>
        </div>

        <!-- Results List -->
        <div class="flex flex-col -mx-4">
            <div class="px-5 pb-6">
                {{ $shows?->onEachSide(1)->links() }}
            </div>

            <!-- Result Item (TV Show) -->
            @forelse($shows as $tvShow)
            <div class="px-4 mb-4" wire:key="show-{{ $tvShow->id }}">
                <div class="shadow-md overflow-hidden hover:bg-gray-100">

                    <!-- Show Info -->
                    <div class="flex flex-col sm:flex-row py-2 justify-between">

                        <div class="flex flex-col xs:flex-row flex-1 space-y-2 xs:space-y-0 xs:space-x-4">
                            <!-- Show Poster -->
                            <img src="{{ $tvShow?->thumb_url }}" alt="No Poster"
                                 class="w-full xs:w-32 max-w-xs h-auto border bg-gray-100 p-1 cursor-pointer rounded-xl object-cover self-center sm:self-auto"
                                 x-on:click.prevent="tvShowClicked($wire, {{$tvShow->id}})"
                            >

                            <div class="flex flex-col flex-1 text-sm text-gray-500 ">
                                <!-- Show Name -->
                                <a href="{{$tvShow->getFullInfoUrl()}}" class="text-base sm:text-lg font-semibold mb-2 hover:text-m-red cursor-pointer w-fit"
                                   x-on:click.prevent="tvShowClicked($wire, {{$tvShow->id}})">
                                    {!! strLimitHighlighted($tvShow->name, 70) !!}
                                </a>
                                <!-- Other Info -->
                                <p class="flex space-x-2 truncate">
                                    <span>{{ sprintf("%s%s%s", $tvShow->start_date?->format('Y'), (empty($tvShow->end_date) ? "" : "-"), $tvShow->end_date?->format('Y')) }},</span>
                                    <span>{!! strLimitHighlighted($tvShow->network, 20, '') !!}, </span>
                                    <span>{{ $tvShow->country }}</span>
                                </p>
                                <p><span class="font-semibold">Genre</span>: <span>{{ $tvShow->getGenresText(6) }}</span></p>
                                <p class="mt-2">{{ $tvShow->getShowDescription(130) }}</p>
                            </div>
                            <div class="pt-1">
                            <span class="bg-gray-200 text-gray-900 text-xs self-start font-medium mr-2 px-2.5 py-0.5 rounded {{$tvShow->isRunning() ? 'bg-green-200':''}}">
                                {{$tvShow->status}}
                            </span>
                            </div>
                        </div>

                        <!-- Subscribe Button -->
                        <div class="pt-2 pr-2 sm:ml-2 text-xs">
                            <livewire:subscribe-button :tv-show="$tvShow" class="w-full min-w-[130px] sm:m-0" wire:key="{{$tvShow?->id}}"/>
                        </div>
                    </div>
                </div>
            </div>
            @empty
                <div class="px-5">No results!</div>
            @endforelse
        </div>
    </div>

    <div class="px-5 pb-6">
        {{ $shows?->onEachSide(1)->links() }}
    </div>
</div>
