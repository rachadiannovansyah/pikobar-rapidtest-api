<?php

namespace Tests\Feature\RdtEvents;

use App\Entities\RdtEvent;
use App\Entities\User;
use Tests\TestCase;

class ShowRdtEventTest extends TestCase
{
    /** @test */
    function can_show_event()
    {
        $rdtEvent = factory(RdtEvent::class)->create();

        $user = new User();
        $user->assignPermissions(['view-events']);

        $this->actingAs($user)
            ->getJson("/api/rdt/events/{$rdtEvent->id}")
            ->assertSuccessful()
            ->assertJsonStructure(['data' => ['event_name', 'host_name']]);
    }
}
