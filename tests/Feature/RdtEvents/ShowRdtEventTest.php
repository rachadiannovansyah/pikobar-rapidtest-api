<?php

namespace Tests\Feature\RdtEvents;

use App\Entities\RdtEvent;
use App\Entities\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowRdtEventTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    function can_show_events()
    {
        $this->withoutExceptionHandling();

        $rdtEvent = factory(RdtEvent::class)->create();

        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->getJson("/api/rdt/events/{$rdtEvent->id}")
            ->assertSuccessful()
            ->assertJsonStructure(['data']);
    }
}
