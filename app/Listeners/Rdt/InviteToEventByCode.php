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

        // Jika pendaftar tidak memiliki pikobar_session_id, skip proses
        if ($applicant->pikobar_session_id === null) {
            return;
        }

        // Jika pendaftar dari Prixa, skip proses
        if (strlen($applicant->pikobar_session_id) === 36) {
            return;
        }

        /**
         * @var RdtEvent $rdtEvent
         */
        $rdtEvent = RdtEvent::where('event_code', $applicant->pikobar_session_id)->first();

        if ($rdtEvent === null) {
            $applicant->pikobar_session_id = null;
            $applicant->save();
            return;
        }

        $eventEndAt = $rdtEvent->end_at;
        $now = now();

        // Jika event sudah berakhir, jangan auto-invite ke event.
        if ($now->gt($eventEndAt)) {
            $applicant->pikobar_session_id = null;
            $applicant->save();
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
