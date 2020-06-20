<?php

namespace Tests\Feature\RdtEvents;

use App\Entities\RdtEvent;
use App\Entities\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteRdtEvent extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_delete_rdt_event()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $rdtEvent = factory(RdtEvent::class)->create();

        $this->actingAs($user)
            ->deleteJson("api/rdt/events/{$rdtEvent->id}")
            ->assertSuccessful()
            ->assertJsonStructure(['success']);

        $this->assertEmpty(RdtEvent::find($rdtEvent->id));

    }

}
