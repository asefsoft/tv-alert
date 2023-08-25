<div>
    <div class="max-w-[220px] mx-auto bg-white shadow-lg rounded-lg overflow-hidden">

        <!-- TV Show Poster -->
        @if($displayPoster)
            <img src="{{ $tvShow?->thumb_url }}" alt="TV Show Poster" class="w-full">
        @endif

        <div class="px-2 py-2">
            <!-- TV Show Name -->
            <h2 class="text-md font-semibold mb-2">{{ $tvShow?->name }}</h2>

            <!-- Next Episode Info -->
            <p class="text-gray-600 mb-3">Next Episode: Episode Title</p>

            <!-- Watch Later and Other Info -->
            <div class="flex justify-between flex-col">

                <!-- Other Info -->
                <div class="flex justify-between flex-col text-gray-600 mb-3">
                    <span>Rating: 9.5</span>
                    <span>Poster: {{ $tvShow?->status }}</span>
                </div>

                <!-- Subscribe Button -->
                <button class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600"
                        wire:click="subscribe"
                >Subscribe
                </button>
            </div>
        </div>
    </div>

</div>
