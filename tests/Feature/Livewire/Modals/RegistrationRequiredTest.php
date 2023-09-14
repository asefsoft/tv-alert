<?php

namespace Tests\Feature\Livewire\Modals;

use App\Livewire\Modals\RegistrationRequired;
use Livewire\Livewire;
use Tests\TestCase;

class RegistrationRequiredTest extends TestCase
{
    /** @test */
    public function test_modal_form_works_ok()
    {
        Livewire::test(RegistrationRequired::class)
            ->assertSeeInOrder(['Please register on our site first to unlock this feature.', 'Login', 'Register'])
            ->dispatch('register-required') // manually dispatching register event, then it should display form to user
            ->assertSet('displayRegisterModal', true) // after dispatch this should be true
            ->call('goToLogin') // simulate login
            ->assertRedirect('login') // must redirect
            ->call('goToRegister') // simulate register
            ->assertRedirect('register') // must redirect
            ->assertStatus(200);
    }
}
