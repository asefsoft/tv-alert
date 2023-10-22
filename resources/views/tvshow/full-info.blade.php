<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Welcome
        </h2>
    </x-slot>

    <div class="py-6 md:py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <livewire:TVShow-Full-Info tvShowId={{$tvshowId}}></livewire:TVShow-Full-Info>
            </div>
        </div>
    </div>
</x-app-layout>
