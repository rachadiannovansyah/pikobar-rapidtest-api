<?php

namespace Tests\Feature\RdtEvents;

use App\Entities\RdtEvent;
use App\Entities\User;
use App\Enums\RdtEventStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateRdtEventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_update_rdt_event()
    {

        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $rdtEvent = factory(RdtEvent::class)->create(['event_name' => 'Event Before update']);

        $this->actingAs($user)
            ->putJson("api/rdt/events/{$rdtEvent->id}", [
            'event_name'     => 'Event After update',
            'event_location' => 'Jl. Becker Street',
            'start_at'       => '2020-06-25 08:20',
            'end_at'         => '2020-06-26 08:20',
            'status'         => RdtEventStatus::DRAFT()
        ])
        ->assertSuccessful()
        ->assertJsonStructure(['success']);

        $this->assertDatabaseHas('rdt_events',[
            'event_name' => 'Event After update'
        ]);

    }
}
