<?php

use App\RdtApplicant;
use App\RdtEvent;
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

            $applicant->event()->associate($event);
            $applicant->save();
        });
    }
}
