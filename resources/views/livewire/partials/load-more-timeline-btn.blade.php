<button class="px-4 pr-10 py-2 text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm"
        wire:loading.attr="disabled"
        wire:click="showMore"

>
    Show More Days >> ({{ $daysToShow }})
    <div class="absolute inline-block ml-2">
        @include('livewire.partials.loading-indicator')
    </div>
</button>
