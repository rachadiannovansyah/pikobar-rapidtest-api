<?php

namespace Tests\Feature\RdtApplicants;

use App\Entities\RdtApplicant;
use App\Entities\User;
use Tests\TestCase;

class ListRdtApplicantTest extends TestCase
{
    /** @test */
    function can_show_all_rdt_applicants()
    {
        factory(RdtApplicant::class, 30)->create();

        $user = new User();
        $user->assignPermissions(['list-applicants']);

        $this->actingAs($user)
            ->getJson("/api/rdt/applicants")
            ->assertSuccessful()
            ->assertJsonStructure(['data', 'meta'])
            ->assertJsonFragment(['total' => 30]);
    }

    /** @test */
    public function cannot_list_applicants_unauthenticated()
    {
        $this->getJson("/api/rdt/applicants")->assertUnauthorized();
    }

    /** @test */
    public function cannot_list_applicants_no_permission()
    {
        $user = new User();

        $this->actingAs($user)
            ->getJson("/api/rdt/applicants")->assertForbidden();
    }
}
