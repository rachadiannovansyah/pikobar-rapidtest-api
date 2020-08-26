<?php

namespace Tests\Feature\RdtEvents;

use App\Entities\RdtEvent;
use App\Entities\User;
use App\Enums\RdtEventStatus;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class UpdateRdtEventTest extends TestCase
{
    /** @test */
    public function can_update_rdt_event()
    {
        $user = new User();

        /**
         * @var RdtEvent $rdtEvent
         */
        $rdtEvent = factory(RdtEvent::class)->make();
        $rdtEvent->start_at = $startAt = new Carbon();
        $rdtEvent->end_at = $endAt = new Carbon();
        $rdtEvent->save();

        $rdtEvent->schedules()->insert([
            'rdt_event_id' => $rdtEvent->id,
            'start_at' => $startAt,
            'end_at' => $endAt,
        ]);

        $this->actingAs($user)
            ->putJson("api/rdt/events/{$rdtEvent->id}", [
            'event_name'     => 'Event After update',
            'event_location' => 'Jl. Becker Street',
            'start_at'       => $startAt->addDay(),
            'end_at'         => $endAt->addDay(),
            'status'         => RdtEventStatus::DRAFT()
        ])
        ->assertSuccessful()
        ->assertJsonStructure(['data' => ['event_name', 'event_code']])
        ->assertJsonFragment([
            'event_name' => 'Event After update',
            'event_location' => 'Jl. Becker Street',
        ]);

        $this->assertDatabaseHas('rdt_events',[
            'event_name' => 'Event After update'
        ]);
    }
}
