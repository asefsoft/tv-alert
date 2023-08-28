<div>
<div class="grid grid-cols-6 gap-4 p-3">
    @php
        $shows = \App\Models\TVShow::getRandomShow(6);
    @endphp

    @foreach($shows as $show)
        <livewire:tvshow-box wire:key="{{ $show->id }}" :tv-show="$show"></livewire:tvshow-box>
    @endforeach

</div>
</div>
