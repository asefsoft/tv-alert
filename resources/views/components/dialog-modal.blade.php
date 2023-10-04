@props(['id' => null, 'maxWidth' => null, 'modalClasses' => ''])

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }} :modalClasses="$modalClasses">
    <div class="px-2 py-2 md:px-6 md:py-4">
        <div class="text-lg font-medium text-gray-900">
            {{ $title }}
        </div>

        <div class="mt-4 text-sm text-gray-600">
            {{ $content }}
        </div>
    </div>

    <div class="flex flex-row justify-end px-6 py-4 bg-gray-100 text-right">
        {{ $footer }}
    </div>
</x-modal>
