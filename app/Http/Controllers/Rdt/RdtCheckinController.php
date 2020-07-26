<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtEvent;
use App\Entities\RdtInvitation;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rdt\RdtCheckinRequest;
use App\Http\Resources\RdtApplicantResource;

class RdtCheckinController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \App\Http\Requests\Rdt\RdtCheckStatusRequest  $request
     * @return \App\Http\Resources\RdtApplicantResource
     */
    public function __invoke(RdtCheckinRequest $request)
    {
        $registrationCode = $request->input('registration_code');

        $eventCode = $request->input('event_code');
        $event     = RdtEvent::where('event_code', $eventCode)->firstOrFail();

        $invitation = RdtInvitation::where('registration_code', $registrationCode)
//            ->where('rdt_event_id', $event->id)
            ->firstOrFail();

        $invitation->attended_at = now();
        $invitation->save();

        $applicant = $invitation->applicant;

        return new RdtApplicantResource($applicant);
    }
}
