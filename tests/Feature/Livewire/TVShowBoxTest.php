<?php

namespace Tests\Feature\Livewire;

use App\Livewire\TVShowBox;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class TVShowBoxTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        Livewire::test(TVShowBox::class)
            ->assertStatus(200);
    }
}
