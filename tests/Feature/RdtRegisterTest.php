<?php

namespace Tests\Feature;

use Tests\TestCase;

class RdtRegisterTest extends TestCase
{
    /** @test */
    public function can_register()
    {
        $this->artisan('db:seed', ['--class' => 'AreasTestSeeder']);

        $this->postJson('/api/rdt/register', [
            'g-recaptcha-response' => 'X',
            'name'                 => 'Test User',
            'nik'                  => '3518814601106254',
            'address'              => 'Address',
            'city_code'            => '32.73',
            'district_code'        => '32.73.07',
            'village_code'         => '32.73.07.1002',
            'email'                => 'test@email.org',
            'phone_number'         => '62857123456',
            'gender'               => 'M',
            'birth_date'           => '1988-11-15',
            'occupation_type'      => 1,
            'workplace_name'       => 'Tempat Kerja',
            'symptoms'             => [1, 2, 3],
            'symptoms_notes'       => 'Notes',
            'symptoms_interaction' => 1,
            'symptoms_activity'    => [1, 2],
        ])
            ->assertSuccessful()
            ->assertJsonStructure(['data' => ['name', 'registration_code', 'status']]);
    }

    /** @test */
    public function cannot_register_nik_invalid()
    {
        $this->postJson('/api/rdt/register', [
            'nik' => '0000',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['nik']);
    }

    /** @test */
    public function cannot_register_required()
    {
        $this->postJson('/api/rdt/register')
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'nik', 'name', 'address', 'city_code', 'district_code', 'village_code', 'email', 'phone_number',
                'gender', 'birth_date', 'occupation_type', 'symptoms', 'symptoms_notes', 'symptoms_interaction',
                'symptoms_activity',
            ]);
    }
}
