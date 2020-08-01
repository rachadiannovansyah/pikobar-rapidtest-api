<?php

use App\Entities\Area;
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
        factory(RdtApplicant::class, 500)->create()->each(function (RdtApplicant $rdtApplicant) {
            $randomCity     = Area::where('parent_code_kemendagri', '32')->inRandomOrder()->first();
            $randomDistrict = $randomCity->children()->inRandomOrder()->first();
            $randomVillage  = $randomDistrict->children()->inRandomOrder()->first();

            $rdtApplicant->city()->associate($randomCity);
            $rdtApplicant->district()->associate($randomDistrict);
            $rdtApplicant->village()->associate($randomVillage);
            $rdtApplicant->save();
        });

        $randomApplicants = RdtApplicant::inRandomOrder()->take(400)->get();

        $randomApplicants->each(function (RdtApplicant $applicant) {
            $event = RdtEvent::inRandomOrder()->first();

            $eventSchedule = $event->schedules()->inRandomOrder()->first();

            $invitation                  = new RdtInvitation();
            $invitation->test_type       = 'PCR';
            $invitation->lab_result_type = 'NEGATIVE';
            $invitation->result_at       = now();
            $invitation->schedule()->associate($eventSchedule);
            $invitation->event()->associate($event);

            $applicant->city_code = $event->city_code;
            $applicant->status    = RdtApplicantStatus::APPROVED();
            $applicant->invitations()->save($invitation);
            $applicant->save();
        });
    }
}
