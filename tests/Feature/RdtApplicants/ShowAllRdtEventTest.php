<?php

namespace Tests\Feature\RdtApplicants;

use App\Entities\RdtApplicant;
use App\Entities\RdtEvent;
use App\Entities\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowAllRdtEventTest extends TestCase
{
    /** @test */
    function can_show_all_rdt_applicants()
    {
        $this->withoutExceptionHandling();

        $this->artisan('db:seed', ['--class' => 'AreasTestSeeder']);

        factory(RdtApplicant::class, 30)->create();

        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->getJson("/api/rdt/applicants")
            ->assertSuccessful()
            ->assertJsonStructure([
                'data' => [
                    0 => [
                        'nik',
                        'name',
                        'email',
                        'phone_number',
                        'province_code',
                        'city_code'
                    ]
                ]
            ]);
    }
}
