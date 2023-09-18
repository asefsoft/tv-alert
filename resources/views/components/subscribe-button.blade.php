@props([
    'isSubscribed'
])
@php
    $state = $isSubscribed ? 'bg-green-500 hover:bg-green-600' : 'bg-blue-500 hover:bg-blue-600'
@endphp

<button {{ $attributes->class(["px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600 " . $state])}}
        wire:loading.attr="disabled"
        x-on:click="subscribeClicked($wire)"
>
    {{ $isSubscribed ? 'Unsubscribe' : 'Subscribe' }}

    {{-- Loading indicator --}}
    <div class="absolute inline-block ml-3">
        @include('livewire.partials.loading-indicator')
    </div>
</button>


<script>
    // clicked on subscribe
    function subscribeClicked(wire) {
        // not logged-in?
        if(window.isAuthenticated === '0') {
            // display register required modal form.
            Livewire.getByName('modals.registration-required')[0].displayRegisterModal = true;
        }
        else {
            // call subscribe on livewire component
            wire.call('subscribe');
        }
    }
</script>
