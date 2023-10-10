<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HorizonPanelTest extends TestCase
{

    // guests can not see horizon login
    public function test_is_unauthorized_for_guests(): void
    {
        $panelPath = config('horizon.path');
        $response = $this->get('/' . $panelPath);

        // 403
        $response->assertStatus(403);
    }

    public function test_is_unauthorized_for_invalid_user(): void
    {
        // set our acceptable email config
        config()->set('horizon.auth_acceptable_email', 'our-acceptable-email@email.com');

        $panelPath = config('horizon.path');

        $user = User::inRandomOrder()->first();

        // make sure auth user's email is different from our acceptable email
        $user->email = 'different-email@email.com';

        $response = $this->actingAs($user)->get('/' . $panelPath);

        // 403
        $response->assertStatus(403);
    }

    public function test_is_authorized_for_invalid_user(): void
    {
        // set our acceptable email config
        config()->set('horizon.auth_acceptable_email', 'our-acceptable-email@email.com');

        $panelPath = config('horizon.path');

        $user = User::inRandomOrder()->first();

        // make sure auth user's email is equal with our acceptable email
        $user->email = 'our-acceptable-email@email.com';

        $response = $this->actingAs($user)->get('/' . $panelPath);

        // 200
        $response->assertStatus(200);
    }
}
