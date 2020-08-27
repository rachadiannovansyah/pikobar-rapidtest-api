<?php

namespace Tests\Feature\RdtEvents;

use App\Entities\RdtEvent;
use App\Entities\User;
use Tests\TestCase;

class ListRdtEvent extends TestCase
{
    /** @test */
    function can_list_events()
    {
        factory(RdtEvent::class, 30)->make()->each(function (RdtEvent $rdtEvent) {
            $rdtEvent->city_code = '32.73';
            $rdtEvent->save();
        });

        $user = new User();
        $user->assignPermissions(['list-events']);

        $this->actingAs($user)
            ->getJson("/api/rdt/events?status=published")
            ->assertSuccessful()
            ->assertJsonStructure(['data', 'meta'])
            ->assertJsonFragment(['total' => 30]);
    }
}
