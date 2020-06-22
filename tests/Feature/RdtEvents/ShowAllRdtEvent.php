<?php

namespace Tests\Feature\RdtEvents;

use App\Entities\RdtEvent;
use App\Entities\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowAllRdtEvent extends TestCase
{
    /** @test */
    function can_show_events()
    {
        $this->withoutExceptionHandling();

        $rdtEvent = factory(RdtEvent::class, 30)->create();

        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->getJson("/api/rdt/events",[
                "perPage" => 5
            ])
            ->assertSuccessful()
            ->assertJsonStructure(['data']);
    }
}
