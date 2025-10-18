<div class="mb-1">
    You have subscribed to <strong>{{\App\Models\User::getAuthUserTotalSubscribedShows()}}</strong> TV shows.

    {{-- Email notification setting option --}}
    <div class="flex mt-2 mb-3">
        <div class="flex items-center h-5">
            <input id="email-subs" aria-describedby="helper-checkbox-text" type="checkbox"
                   wire:model.live="userEmailSubscribed"
                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 cursor-pointer">
        </div>
        <div class="ml-2 text-sm">
            <label for="email-subs" class="font-bold text-gray-900 cursor-pointer">Send Email Notification?</label>
            <span class="text-gray-600">(whenever there is a new episode)</span>
        </div>
    </div>
</div>
