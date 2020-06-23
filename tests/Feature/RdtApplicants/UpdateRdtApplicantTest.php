<?php

namespace Tests\Feature\RdtApplicants;

use App\Entities\RdtApplicant;
use App\Entities\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateRdtApplicantTest extends TestCase
{
    /** @test */
    public function can_update_applicant()
    {
        $this->withoutExceptionHandling();

        $this->artisan('db:seed', ['--class' => 'AreasTestSeeder']);

        $user = factory(User::class)->create();

        $rdtApplicant = factory(RdtApplicant::class)->create();

        $this->actingAs($user)
            ->putJson("/api/rdt/applicants/{$rdtApplicant->id}", [
                "name" => "Ahmad Udate",
                "nik" => "3518814601106254",
                "address" => "Address",
                "city_code" => "32.73",
                "district_code" => "32.73.07",
                "village_code" => "32.73.07.1002",
                "email" => "test@email.org",
                "phone_number" => "62857123456",
                "gender" => "M",
                "birth_date" => "1988-11-15",
                "occupation_type" => 1,
                "workplace_name" => "Tempat Kerja",
                "symptoms" => [1, 2, 3],
                "symptoms_notes" => "notes",
                "symptoms_interaction" => 1,
                "symptoms_activity" => [1, 2],
                "latitude" => "-6.874959",
                "longitude" => "107.572333"
            ])->assertSuccessful()
            ->assertJsonStructure(['success']);

        $this->assertDatabaseHas('rdt_applicants', [
            "name" => "Ahmad Udate",
            "nik" => "3518814601106254",
            "address" => "Address",
            "city_code" => "32.73",
            "district_code" => "32.73.07",
            "village_code" => "32.73.07.1002",
            "email" => "test@email.org",
            "phone_number" => "62857123456",
            "gender" => "M",
            "birth_date" => "1988-11-15",
            "occupation_type" => 1,
            "workplace_name" => "Tempat Kerja"
        ]);
    }
}
