<div>
    <x-dialog-modal max-width="2xl" wire:model="displayTvShowModal">
        <x-slot name="title">
            TV Show Info
        </x-slot>

        <x-slot name="content">
            <livewire:TVShow-Full-Info isModalMode="true"></livewire:TVShow-Full-Info>
        </x-slot>

        <x-slot name="footer">
{{--            <x-secondary-button wire:click="$toggle('displayRegisterModal')" wire:loading.attr="disabled">--}}
{{--                Cancel--}}
{{--            </x-secondary-button>--}}

{{--            <x-button class="ml-2 bg-green-500 hover:bg-green-600" wire:click="goToLogin"--}}
{{--                      wire:loading.attr="disabled">--}}
{{--                Login--}}
{{--            </x-button>--}}
{{--            <x-button class="ml-2 bg-green-500 hover:bg-green-600" wire:click="goToRegister"--}}
{{--                      wire:loading.attr="disabled">--}}
{{--                Register--}}
{{--            </x-button>--}}
        </x-slot>
    </x-dialog-modal>
</div>
