<div class="px-3 my-2 shadow-md">
    {{-- Title --}}
    <div class="grid grid-cols-1 sm:grid-cols-2">
        <h2 class="text-xl font-semibold mb-8 underline underline-offset-[14px] decoration-4 decoration-gray-300">
            {{ $title }}
        </h2>

        {{-- Subscribed Shows Filter --}}
        @if($canToggleSubscribedShowsFilter)
        <div class="flex justify-end text-right mt-2">
            <div class="mr-2 text-sm">
                <label for="helper-checkbox-{{$type}}" class="font-medium text-gray-900 cursor-pointer">Only Subscribed Shows?</label>
            </div>
            <div class="flex items-center h-5">
                <input id="helper-checkbox-{{$type}}" aria-describedby="helper-checkbox-text" type="checkbox"
                       wire:model.live="displayOnlySubscribedShows"
                       wire:key="{{$type}}"
                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 cursor-pointer">
            </div>
        </div>
        @endif
    </div>

    @if(! empty($shows) && $shows->count() > 0)
        {{-- TVShow List --}}
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
            @foreach($shows as $show)
                <livewire:tvshow-box wire:key="{{$show->id}}"
                                     :tv-show="$show"
                                     displayLastEpDate="{{ $displayLastEpDate }}"
                ></livewire:tvshow-box>
            @endforeach
        </div>

        <div class="py-5 px-3">
            {{ $shows->onEachSide(1)->links() }}
        </div>
    @else
        {{-- Empty State --}}
        <section class="">
            <div class="py-8 px-4 max-w-screen-xl text-center lg:py-16 flex flex-col items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                     class="w-16 h-16 text-brown-400 content-center">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.182 16.318A4.486 4.486 0 0012.016 15a4.486 4.486 0 00-3.198 1.318M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z" />
                </svg>

                <div class="">
                    <h1 class="mb-4 text-2xl font-bold tracking-tight leading-none text-gray-900 md:text-3xl lg:text-4xle">
                        No tv show available!</h1>
                    <p class="mb-8 text-lg font-normal text-gray-500 lg:text-xl sm:px-16 lg:px-48">
                        There is no tv show available in current list.
                        if you did not subscribe to any tv show, add some to your subscription list to see them here.
                    </p>
                    {{--                <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0 sm:space-x-4">--}}
                    {{--                    <a href="#" class="inline-flex justify-center items-center py-3 px-5 text-base font-medium text-center text-white rounded-lg bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-900">--}}
                    {{--                        Get started--}}
                    {{--                        <svg class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">--}}
                    {{--                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>--}}
                    {{--                        </svg>--}}
                    {{--                    </a>--}}
                    {{--                    <a href="#" class="inline-flex justify-center items-center py-3 px-5 text-base font-medium text-center text-gray-900 rounded-lg border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 dark:text-white dark:border-gray-700 dark:hover:bg-gray-700 dark:focus:ring-gray-800">--}}
                    {{--                        Learn more--}}
                    {{--                    </a>--}}
                    {{--                </div>--}}</div>
            </div>
        </section>
    @endif

</div>


