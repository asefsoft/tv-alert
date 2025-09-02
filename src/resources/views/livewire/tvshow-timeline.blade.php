@php /** @var $section \App\TVShow\Timeline\TimelineSection **/  /** @var $tvShow \App\Models\TVShow **/  @endphp
<div wire:poll.100s="updatePollingStats" class="p-3 sm:p-5 mb-4 border border-gray-100 space-y-4 sm:space-y-6 rounded-lg">

    <div class="pt-2 flex justify-between sm:flex-row flex-col">
        <div class="mb-1">
            You have subscribed to <strong>{{\App\Models\User::getAuthUserTotalSubscribedShows()}}</strong> TV shows.

            {{-- Email notification setting option --}}
            <div class="flex mt-2 mb-3">
                <div class="flex items-center h-5">
                    <input id="email-subs" aria-describedby="helper-checkbox-text" type="checkbox"
                           wire:model.live="userEmailSubscribed"
                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 cursor-pointer">
                </div>
                <div class="ml-2 text-sm">
                    <label for="email-subs" class="font-bold text-gray-900 cursor-pointer">Send Email Notification?</label>
                    <span class="text-gray-600">(whenever there is a new episode)</span>
                </div>
            </div>
        </div>

        @if($timeline->getInfo()->hasPastTimeline())
        @include('livewire.partials.load-more-timeline-btn')
        @endif

    </div>

    @foreach($timeline->getSections() as $secKey => $section)
        <ol class="divide-y divider-white-300 shadow-md" wire:key="section-{{$secKey}}">
            <li class="px-3 xs:px-4 py-3 rounded {{ $section->fm->getSectionCssClasses() }}">

                {{-- Section Title--}}
                <div class="text-xl font-semibold mb-3">{{ $section->getTitle() }}
                    <span class="text-sm text-gray-400" title="{{ $daysToShow }} days">
                        {{$section->fm->getSectionTimePeriod()}}
                    </span>
                </div>

                <div class="grid grid-cols-1 xs:grid-cols-2 lg:grid-cols-3 gap-2 lg:gap-3">
                    @forelse($section->getTvShows() as $tvShow)
                        <div class="p-4 border border-{{$section->fm->getSectionMainColor()}}-300 rounded-lg group
                         bg-{{$section->fm->getSectionMainColor()}}-100 hover:bg-{{$section->fm->getSectionMainColor()}}-300 cursor-pointer"
                             x-data='{ tvshowId: {{$tvShow->id}} }'
                             x-on:click="tvShowClicked($wire, tvshowId)"
                             wire:key="show-{{$tvShow->id}}"
                        >
                            {{-- Show Name--}}
                            <div class="text-md font-semibold text-gray-900 group-hover:text-m-red">
                                {{$tvShow->name}}
                                <div class="flex items-center justify-between text-sm text-gray-400 overflow-hidden">
                                    {{$tvShow->start_date?->format('Y')}}, {{$tvShow->network}}, {{$tvShow->country}}
                                    @include('livewire.partials.imdb-link')
                                </div>
                            </div>
                            <ol class="mt-1 divide-y divider-gray-200 ">
                                <li>
                                    <div class="items-center py-3 flex">
                                        <img class="w-12 h-16 mb-3 mr-3 rounded-md sm:mb-0"
                                             src="{{ $tvShow->thumb_url }}" alt="No Poster"/>
                                        <div class="text-gray-600 ">
                                            <div class="text-base font-normal">
                                            <span class="font-medium text-gray-900">
                                                {{ $section->fm->getEpisodeName($tvShow) }}
                                            </span>
                                            </div>
                                            <div
                                                class="text-sm font-normal">{{ $section->fm->getEpisodeInfo($tvShow) }}</div>
                                            <time class="inline-flex items-center text-xs font-normal text-gray-500"
                                                  title="{{$section->fm->getEpisodeDate($tvShow, 'default')}}">
                                                {{ $section->fm->getEpisodeDate($tvShow) }}
                                            </time>
                                        </div>
                                    </div>
                                </li>
                            </ol>
                        </div>
                    @empty
                        <div class="text-gray-700 my-4">No TV Show episode for this time period!</div>
                    @endforelse
                </div>
            </li>
        </ol>
    @endforeach

    @if($timeline->getInfo()->hasFutureTimeline())
        <div class="flex sm:flex-row flex-col justify-end">
            @include('livewire.partials.load-more-timeline-btn')
        </div>
    @endif

</div>
