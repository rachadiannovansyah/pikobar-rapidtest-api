<?php

use App\Entities\RdtApplicant;
use App\Entities\RdtEvent;
use App\Entities\RdtInvitation;
use App\Enums\RdtApplicantStatus;
use Illuminate\Database\Seeder;

class RdtApplicantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(RdtApplicant::class, 50)->create();

        $randomApplicants = RdtApplicant::inRandomOrder()->take(40)->get();

        $randomApplicants->each(function (RdtApplicant $applicant) {
            $event = RdtEvent::inRandomOrder()->first();

            $invitation = new RdtInvitation();
            $invitation->event()->associate($event);
            $invitation->test_type       = 'PCR';
            $invitation->lab_result_type = 'NEGATIVE';
            $invitation->result_at       = now();

            $applicant->status = RdtApplicantStatus::APPROVED();
            $applicant->invitations()->save($invitation);
            $applicant->save();
        });
    }
}
