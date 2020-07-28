<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtEvent;
use App\Entities\RdtInvitation;
use App\Http\Controllers\Controller;
use App\Http\Resources\RdtEventResource;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class RdtEventParticipantAddController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entities\RdtEvent  $rdtEvent
     * @return \App\Http\Resources\RdtEventResource
     */
    public function __invoke(Request $request, RdtEvent $rdtEvent)
    {
        $applicants   = $request->input('applicants');

        foreach ($applicants as $applicant) {
            $invitation = RdtInvitation::firstOrNew(['rdt_applicant_id' => $applicant['rdt_applicant_id']]);
            $invitation->event()->associate($rdtEvent);
            $invitation->rdt_event_schedule_id = $applicant['rdt_event_schedule_id'];
            $invitation->save();
        }

        $rdtEvent->load('invitations');

        return new RdtEventResource($rdtEvent);
    }
}
