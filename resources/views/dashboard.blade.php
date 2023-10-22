<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6 md:py-8">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg space-y-5 md:space-y-8">
                <livewire:TVShow-Group title="Recent shows" type="recent-shows"></livewire:TVShow-Group>
                <livewire:TVShow-Group title="Your subscribed shows" type="subscribed-shows"></livewire:TVShow-Group>
                <livewire:TVShow-Group title="Last week shows" type="last-7-days-shows"></livewire:TVShow-Group>
            </div>
        </div>
    </div>
</x-app-layout>

