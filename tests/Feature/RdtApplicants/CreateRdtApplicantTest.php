<?php

namespace Tests\Feature\RdtApplicants;

use App\Entities\User;
use App\Enums\RdtApplicantStatus;
use Tests\TestCase;

class CreateRdtApplicantTest extends TestCase
{
    /** @test */
    public function can_create_applicant()
    {
        $user = new User();
        $user->assignPermissions(['create-applicants']);

        $this->actingAs($user)
            ->postJson("/api/rdt/applicants", [
                'name'                  => 'Ahmad',
                'nik'                   => '3518814601106254',
                'address'               => 'Address',
                'city_code'             => '32.73',
                'district_code'         => '32.73.07',
                'village_code'          => '32.73.07.1002',
                'phone_number'          => '0857123456',
                'gender'                => 'M',
                'birth_date'            => '1988-11-15',
                'occupation_type'       => 1,
                'workplace_name'        => 'Tempat Kerja',
                'symptoms'              => [1, 2, 3],
                'symptoms_notes'        => 'notes',
                'symptoms_interaction'  => 1,
                'symptoms_activity'     => [1, 2],
                'latitude'              => '-6.874959',
                'longitude'             => '107.572333',
                'status'                => RdtApplicantStatus::NEW(),
            ])
            ->assertSuccessful()
            ->assertJsonStructure(['data' => ['name', 'registration_code']]);

        $this->assertDatabaseHas('rdt_applicants',[
            'name'                  => 'AHMAD',
            'nik'                   => '3518814601106254',
            'address'               => 'ADDRESS',
            'city_code'             => '32.73',
            'district_code'         => '32.73.07',
            'village_code'          => '32.73.07.1002',
            'phone_number'          => '0857123456',
            'gender'                => 'M',
            'occupation_type'       => 1,
            'workplace_name'        => 'TEMPAT KERJA'
        ]);
    }

    /** @test */
    public function cannot_create_applicant_unauthenticated()
    {
        $this->postJson("/api/rdt/applicants")->assertUnauthorized();
    }

    /** @test */
    public function cannot_create_applicant_no_permission()
    {
        $user = new User();

        $this->actingAs($user)
            ->postJson("/api/rdt/applicants")
            ->assertForbidden();
    }
}
