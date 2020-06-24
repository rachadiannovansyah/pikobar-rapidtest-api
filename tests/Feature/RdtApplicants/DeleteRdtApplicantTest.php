<?php

namespace Tests\Feature\RdtApplicants;

use App\Entities\RdtApplicant;
use App\Entities\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteRdtApplicantTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function can_delete_rdt_applicant()
    {
        $this->withoutExceptionHandling();

        $this->artisan('db:seed', ['--class' => 'AreasTestSeeder']);

        $user = factory(User::class)->create();

        $rdtApplicant = factory(RdtApplicant::class)->create();

        $this->actingAs($user)
            ->deleteJson("api/rdt/applicants/{$rdtApplicant->id}")
            ->assertSuccessful()
            ->assertJsonStructure(['success']);

        $this->assertEmpty(RdtApplicant::find($rdtApplicant->id));

    }
}
