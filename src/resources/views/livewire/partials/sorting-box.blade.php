{{--        <div class="flex">field: // {{ $sortField }} //</div>--}}
{{--        <div class="flex">query: <br>{{ $this->getQuery() }} </div>--}}
<div class="flex justify-start pb-5 flex-col sm:flex-row">
    <div class="break-words">
        <div class="flex items-center grow mb-1 sm:mb-0">
            {{--  Sort Icon--}}
            <div class="flex shrink-0 mr-2">
                <svg style="width: 24px; height: 24px; ">
                    <use xlink:href="#sort">
                        <symbol id="sort" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                  d="M6 15.793L3.707 13.5l-1.414 1.414 4 4a1 1 0 001.414 0l4-4-1.414-1.414L8 15.793V5H6v10.793zM22 5H10v2h12V5zm0 4H12v2h10V9zm0 4h-8v2h8v-2zm-6 4h6v2h-6v-2z"
                                  clip-rule="evenodd"></path>
                        </symbol>
                    </use>
                </svg>
            </div>
            <p class="grow cursor-pointer whitespace-nowrap text-gray-700  text-body2-strong">
                <span class="relative mr-2">Sort By: </span>
            </p></div>
    </div>

    <div class="contents">
        @foreach($validSortFields as $sField)
            <span
                    class="cursor-pointer whitespace-nowrap mb-1 sm:mb-0 ml-8 sm:ml-0 mr-3
                    {{ $sortField === $sField['name'] && $sortOrder === $sField['order'] ? 'text-red-400 font-bold hover:text-red-600 ' : 'text-gray-500 hover:text-gray-700 ' }}"
                    wire:click="changeSortField('{{$sField['id']}}')"
            >
                    {{$sField['text']}}
                </span>
        @endforeach
    </div>
</div>
