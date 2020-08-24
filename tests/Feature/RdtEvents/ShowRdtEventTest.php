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
    function can_show_event()
    {
        $rdtEvent = factory(RdtEvent::class)->create();

        $user = new User();

        $this->actingAs($user)
            ->getJson("/api/rdt/events/{$rdtEvent->id}")
            ->assertSuccessful()
            ->assertJsonStructure(['data' => ['event_name', 'host_name']]);
    }
}
