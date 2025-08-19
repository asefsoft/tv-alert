<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Welcome to Series Alert
        </h2>
    </x-slot>
    <section class="bg-blue-50 py-8">
        <div class="max-w-5xl mx-auto px-4 text-center">
            <h3 class="text-2xl font-bold text-blue-900 mb-4">Stay Updated with Series Alert</h3>
            <p class="text-lg text-gray-700 mb-6">
                Series Alert helps you never miss an episode of your favorite TV shows. Subscribe to your favorite series and receive email notifications whenever a new episode is released.
            </p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center transition transform hover:-translate-y-1 hover:shadow-lg hover:border-blue-500 border border-transparent">
                    <div class="mb-3 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 17v1a3 3 0 01-6 0v-1M12 7v6m0 0l-2-2m2 2l2-2M12 3v4m8 4a8 8 0 11-16 0 8 8 0 0116 0z" />
                        </svg>
                    </div>
                    <h4 class="font-semibold text-blue-800 mb-2">Email Notifications</h4>
                    <p class="text-gray-600 text-center">Get instant alerts when new episodes of your subscribed shows are available.</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center transition transform hover:-translate-y-1 hover:shadow-lg hover:border-blue-500 border border-transparent">
                    <div class="mb-3 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h4 class="font-semibold text-blue-800 mb-2">Interactive Timeline</h4>
                    <p class="text-gray-600 text-center">View upcoming and past episodes in a clear, easy-to-use timeline.</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center transition transform hover:-translate-y-1 hover:shadow-lg hover:border-blue-500 border border-transparent">
                    <div class="mb-3 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.657 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h4 class="font-semibold text-blue-800 mb-2">Personalized Dashboard</h4>
                    <p class="text-gray-600 text-center">Track your subscriptions and manage notifications all in one place.</p>
                </div>
            </div>
            <a href="{{ route('register') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                Get Started
            </a>
        </div>
    </section>
    <div class="py-6 md:py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <livewire:TVShow-Group title="Recent shows" type="recent-shows"></livewire:TVShow-Group>
                <livewire:TVShow-Group title="Last week shows" type="last-7-days-shows"></livewire:TVShow-Group>
            </div>
        </div>
    </div>
</x-app-layout>
