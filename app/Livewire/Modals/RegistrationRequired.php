<?php

namespace App\Livewire\Modals;

use Livewire\Attributes\On;
use Livewire\Component;

class RegistrationRequired extends Component
{
    public bool $displayRegisterModal = false;

    #[On('register-required')]
    public function displayRegisterRequiredModal() {
        $this->displayRegisterModal = true;
    }

    public function goToRegister() {
        return redirect()->route('register');
    }
    public function goToLogin() {
        return redirect()->route('login');
    }
    public function render()
    {
        return view('livewire.modals.registration-required');
    }
}
