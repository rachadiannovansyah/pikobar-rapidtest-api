<?php

namespace Tests\Feature\RdtEvents;

use App\Entities\RdtEvent;
use App\Entities\User;
use Tests\TestCase;

class DeleteRdtEventTest extends TestCase
{
    /** @test */
    public function can_delete_rdt_event()
    {
        $user = new User();

        $rdtEvent = factory(RdtEvent::class)->create();

        $this->actingAs($user)
            ->deleteJson("api/rdt/events/{$rdtEvent->id}")
            ->assertSuccessful()
            ->assertJsonStructure(['message'])
            ->assertJsonFragment(['message' => 'DELETED']);

        $this->assertSoftDeleted('rdt_events', ['id' => $rdtEvent->id]);
    }
}
