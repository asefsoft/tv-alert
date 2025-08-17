<x-app-layout>
    <x-slot name="title">
        {{ $title }}
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Your Subscribed TV Series
        </h2>
    </x-slot>

    <div class="py-6 md:py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <livewire:TVShow-Group title="Your subscribed shows" perPage="18" canSort="true" type="subscribed-shows"></livewire:TVShow-Group>
            </div>
        </div>
    </div>
</x-app-layout>
