<x-app-layout>
    <x-slot name="title">
        {{ $title }}
    </x-slot>

    <x-slot name="header">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Your Subscribed TV Series
            </h2>
            <div class="bg-indigo-100 mt-2 md:mt-0 text-indigo-800 px-4 py-2 rounded-full font-medium text-sm">
                {{ \App\Models\User::getAuthUserTotalSubscribedShows() }} Shows Subscribed
            </div>
        </div>
    </x-slot>

    <div class="py-6 md:py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <livewire:TVShow-Group
                    title="Your subscribed TV shows" perPage="18" canSort="true"
                    type="subscribed-shows"
                    displayLastEpDate="true"
                >

                </livewire:TVShow-Group>
            </div>
        </div>
    </div>
</x-app-layout>
