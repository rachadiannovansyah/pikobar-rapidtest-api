<?php

namespace Tests\Feature\RdtApplicants;

use App\Entities\RdtApplicant;
use App\Entities\User;
use Tests\TestCase;

class UpdateRdtApplicantTest extends TestCase
{
    /** @test */
    public function can_update_applicant()
    {
        $user = new User();
        $user->assignPermissions(['edit-applicants']);

        /**
         * @var RdtApplicant $rdtApplicant
         */
        $rdtApplicant = factory(RdtApplicant::class)->create();

        $this->actingAs($user)
            ->putJson("/api/rdt/applicants/{$rdtApplicant->id}", [
                'name'                 => 'ahmad update',
                'nik'                  => '3518814601106254',
                'address'              => 'Address baru',
                'city_code'            => '32.73',
                'district_code'        => '32.73.07',
                'village_code'         => '32.73.07.1002',
                'phone_number'         => '0857123456',
                'gender'               => 'M',
                'birth_date'           => '1988-11-15',
                'occupation_type'      => 1,
                'workplace_name'       => 'Tempat kerja Baru',
                'symptoms'             => [1, 2, 3],
                'symptoms_notes'       => 'notes',
                'symptoms_interaction' => 1,
                'symptoms_activity'    => [1, 2],
                'latitude'             => '-6.874959',
                'longitude'            => '107.572333'
            ])
            ->assertSuccessful()
            ->assertJsonStructure(['data' => ['name', 'registration_code']]);

        $this->assertDatabaseHas('rdt_applicants', [
            'name'            => 'AHMAD UPDATE',
            'nik'             => '3518814601106254',
            'address'         => 'ADDRESS BARU',
            'city_code'       => '32.73',
            'district_code'   => '32.73.07',
            'village_code'    => '32.73.07.1002',
            'phone_number'    => '0857123456',
            'gender'          => 'M',
            'occupation_type' => 1,
            'workplace_name'  => 'TEMPAT KERJA BARU'
        ]);
    }
}
