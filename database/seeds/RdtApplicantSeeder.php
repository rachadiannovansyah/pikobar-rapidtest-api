<?php

use App\Entities\RdtApplicant;
use App\Entities\RdtEvent;
use App\Entities\RdtInvitation;
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

            $applicant->invitations()->save($invitation);
            $applicant->save();
        });
    }
}
