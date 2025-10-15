<x-app-layout>
    <x-slot name="title">
        Popular TV Shows
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Popular TV Shows
        </h2>
    </x-slot>

    <div class="py-6 md:py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            {{-- All-time Popular Shows --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <livewire:t-v-show-group
                    type="popular-shows"
                    title="Most Popular TV Shows"
                    :can-sort="true"
                    :per-page="12" />
            </div>

            {{-- Recent Popular Shows --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <livewire:t-v-show-group
                    type="recent-popular-shows"
                    title="Trending New Shows"
                    :can-sort="true"
                    :per-page="12" />
            </div>

            {{-- Ongoing Popular Shows --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <livewire:t-v-show-group
                    type="ongoing-popular-shows"
                    title="Popular Shows Still Running"
                    :can-sort="true"
                    :per-page="12" />
            </div>
        </div>
    </div>
</x-app-layout>
