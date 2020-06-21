<?php

namespace Tests\Feature\RdtEvents;

use App\Entities\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateRdtEventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_event()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->postJson('/api/rdt/events',[
            'event_name'        => 'Event Name',
            'event_location'    => 'Jl. Angrek No. 45',
            'status'            => 'draft',
            'start_at'          => '2020-01-01T00:00:00.000000Z',
            'end_at'            => '2020-01-01T00:00:00.000000Z'
        ])
        ->assertSuccessful()
        ->assertJsonStructure(['success']);
    }
}
