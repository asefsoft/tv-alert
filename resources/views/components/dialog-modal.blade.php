@props(['id' => null, 'maxWidth' => null, 'modalClasses' => ''])

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }} :modalClasses="$modalClasses">
    <div class="px-2 py-2 md:px-6 md:py-4">
        <div class="text-lg font-medium text-gray-900 flex justify-between">
            <span>{{ $title }}</span>
            <button @click="show = false" class="inline-flex items-center justify-center p-1 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="mt-4 text-sm text-gray-600">
            {{ $content }}
        </div>
    </div>

    @if(!empty($footer))
    <div class="flex flex-row justify-end px-6 py-4 bg-gray-100 text-right">
        {{ $footer }}
    </div>
    @endif
</x-modal>
