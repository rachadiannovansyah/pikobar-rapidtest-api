<?php

namespace Tests\Feature\RdtApplicants;

use App\Entities\RdtApplicant;
use App\Entities\User;
use Tests\TestCase;

class DeleteRdtApplicantTest extends TestCase
{
    /** @test */
    public function can_delete_rdt_applicant()
    {
        $user = new User();
        $user->assignPermissions(['delete-applicants']);

        /**
         * @var RdtApplicant $rdtApplicant
         */
        $rdtApplicant = factory(RdtApplicant::class)->create();

        $this->actingAs($user)
            ->deleteJson("api/rdt/applicants/{$rdtApplicant->id}")
            ->assertSuccessful()
            ->assertJsonStructure(['message'])
            ->assertJsonFragment(['message' => 'DELETED']);

        $this->assertSoftDeleted('rdt_applicants', ['id' => $rdtApplicant->id]);
    }

    /** @test */
    public function cannot_delete_applicant_unauthenticated()
    {
        /**
         * @var RdtApplicant $rdtApplicant
         */
        $rdtApplicant = factory(RdtApplicant::class)->create();

        $this->deleteJson("api/rdt/applicants/{$rdtApplicant->id}")->assertUnauthorized();
    }

    /** @test */
    public function cannot_delete_applicant_no_permission()
    {
        $user = new User();

        /**
         * @var RdtApplicant $rdtApplicant
         */
        $rdtApplicant = factory(RdtApplicant::class)->create();

        $this->actingAs($user)
            ->deleteJson("api/rdt/applicants/{$rdtApplicant->id}")
            ->assertForbidden();
    }
}
