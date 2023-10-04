<div>
    <x-dialog-modal max-width="2xl" wire:model="displayTvShowModal">
        <x-slot name="title">
            TV Show Info
        </x-slot>

        <x-slot name="content">
            <livewire:TVShow-Full-Info isModalMode="true"></livewire:TVShow-Full-Info>
        </x-slot>

    </x-dialog-modal>
</div>
