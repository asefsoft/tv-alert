<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg space-y-5 md:space-y-8">
                <livewire:tvshow-group title="Recent shows" type="recent-shows"></livewire:tvshow-group>
                <livewire:tvshow-group title="Your subscribed shows" type="subscribed-shows"></livewire:tvshow-group>
                <livewire:tvshow-group title="Last week shows" type="last-7-days-shows"></livewire:tvshow-group>
            </div>
        </div>
    </div>
</x-app-layout>

