<?php

namespace Tests\Feature\RdtApplicants;

use App\Entities\RdtApplicant;
use App\Entities\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowRdtApplicantTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_show_applicant()
    {
        $this->withoutExceptionHandling();

        $this->artisan('db:seed', ['--class' => 'AreasTestSeeder']);

        $user = factory(User::class)->create();

        $rdtApplicant = factory(RdtApplicant::class)->create();

        $this->actingAs($user)
            ->getJson("/api/rdt/applicants/{$rdtApplicant->id}")
            ->assertSuccessful()
            ->assertJsonStructure([
                'data' => ['name','nik','address']
            ]);

    }
}
