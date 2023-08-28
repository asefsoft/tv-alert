<?php

namespace Tests\Feature\Livewire;

use App\Livewire\TVShowGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class TVShowGroupTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        Livewire::test(TVShowGroup::class)
            ->assertStatus(200);
    }
}
