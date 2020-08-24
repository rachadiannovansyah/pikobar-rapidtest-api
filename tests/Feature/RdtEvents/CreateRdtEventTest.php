<?php

namespace Tests\Feature\RdtEvents;

use App\Entities\RdtEvent;
use App\Entities\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class CreateRdtEventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_event()
    {
        $user = new User();

        /**
         * @var RdtEvent $rdtEvent
         */
        $rdtEvent = factory(RdtEvent::class)->make();

        $this->actingAs($user)
            ->postJson('/api/rdt/events',[
            'event_name'        => 'Event Name',
            'host_name'         => $rdtEvent->host_name,
            'event_location'    => $rdtEvent->event_location,
            'city_code'         => '32.73',
            'status'            => $rdtEvent->status,
            'start_at'          => $startAt = new Carbon(),
            'end_at'            => $endAt = new Carbon(),
            'schedules'         => [
                [
                    'start_at' => $startAt,
                    'end_at' => $endAt,
                ]
            ],
        ])
        ->assertSuccessful()
        ->assertJsonStructure(['data' => ['event_name', 'event_code']])
        ->assertJsonFragment([
            'event_name' => 'Event Name',
            'host_name' => 'Host Name',
        ]);
    }
}
