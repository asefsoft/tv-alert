<div>
    <x-dialog-modal max-width="lg" wire:model="displayRegisterModal" modalClasses="z-[100]">
        <x-slot name="title">
            Registration Required
        </x-slot>

        <x-slot name="content">
            <p>Please register on our site to unlock this feature.</p>
            <p>If you already have an account, click the Login button. Otherwise, use the Register button.</p>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('displayRegisterModal')" wire:loading.attr="disabled">
                Cancel
            </x-secondary-button>

            <x-button class="ml-2 bg-green-500 hover:bg-green-600" wire:click="goToLogin"
                      wire:loading.attr="disabled">
                Login
            </x-button>
            <x-button class="ml-2 bg-green-500 hover:bg-green-600" wire:click="goToRegister"
                      wire:loading.attr="disabled">
                Register
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
