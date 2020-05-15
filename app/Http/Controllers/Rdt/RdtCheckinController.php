<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtApplicant;
use App\Entities\RdtEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rdt\RdtCheckStatusRequest;
use App\Http\Resources\RdtApplicantResource;

class RdtCheckinController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \App\Http\Requests\Rdt\RdtCheckStatusRequest  $request
     * @return \App\Http\Resources\RdtApplicantResource
     */
    public function __invoke(RdtCheckStatusRequest $request)
    {
        $registrationCode = $request->input('registration_code');

        $eventCode = $request->input('event_code');
        $event     = RdtEvent::where('event_code', $eventCode)->firstOrFail();

        $applicant = RdtApplicant::where('registration_code', $registrationCode)
//            ->where('rdt_event_id', $event->id)
            ->firstOrFail();

        $applicant->attended_at = now();
        $applicant->save();

        return new RdtApplicantResource($applicant);
    }
}
