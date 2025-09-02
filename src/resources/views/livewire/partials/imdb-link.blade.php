@if($tvShow?->has_imdb_info)
    <a href="{{ $tvShow?->imdbinfo?->imdb_url }}"
       rel="noopener noreferrer"
       target="_blank"
       class="inline-flex items-center space-x-1 border border-gray-300 rounded pr-1 text-sm hover:bg-gray-100 transition"
       title="Votes: {{ number_format($tvShow?->imdbinfo?->votes) }}">

        <!-- IMDb badge -->
        <span class="bg-yellow-400 text-black font-semibold px-1.5 py-0.5 rounded-sm text-[0.68rem] leading-3">IMDb</span>

        <!-- Rating -->
        <span class="text-gray-800 font-semibold text-[0.68rem] leading-3">{{ $tvShow?->getImdbRating() }}</span>
    </a>


@endif
