<?php

namespace App\Listeners\Rdt;

use App\Entities\RdtEvent;
use App\Entities\RdtInvitation;
use App\Enums\RdtApplicantStatus;
use App\Events\Rdt\ApplicantRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class InviteToEventByCode
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param \App\Events\Rdt\ApplicantRegistered $event
     * @return void
     */
    public function handle(ApplicantRegistered $event)
    {
        $applicant = $event->applicant;

        if ($applicant->pikobar_session_id !== null) {
            /**
             * @var RdtEvent $rdtEvent
             */
            $rdtEvent = RdtEvent::where('referral_code', $applicant->pikobar_session_id)->first();

            if ($rdtEvent === null) {
                return;
            }

            $invitation = new RdtInvitation();
            $invitation->registration_code = $applicant->registration_code;

            $invitation->event()->associate($rdtEvent);
            $invitation->applicant()->associate($applicant);
            $invitation->save();

            $applicant->status = RdtApplicantStatus::APPROVED();
            $applicant->save();
        }
    }
}
