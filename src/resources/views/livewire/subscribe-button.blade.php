@php
    $state = $isSubscribed ? 'bg-green-500 hover:bg-green-600' : 'bg-blue-500 hover:bg-blue-600'
@endphp

<button class="px-4 py-2 text-white text-sm bg-blue-500 rounded {{$state}} {{ $cssClasses }}"
        wire:loading.attr="disabled"
        x-on:click.prevent="subscribeClicked($wire)"

>
    {{ $isSubscribed ? 'Unsubscribe' : 'Subscribe' }}

    {{-- Loading indicator --}}
    @if($showLoadingIndicator)
    <div class="absolute inline-block ml-3">
        @include('livewire.partials.loading-indicator')
    </div>
    @endif
</button>
