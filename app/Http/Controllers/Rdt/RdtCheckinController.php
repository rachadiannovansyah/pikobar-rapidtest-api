<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtEvent;
use App\Entities\RdtInvitation;
use App\Events\Rdt\ApplicantEventCheckin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rdt\RdtCheckinRequest;
use App\Http\Resources\RdtApplicantResource;
use Illuminate\Validation\ValidationException;

class RdtCheckinController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \App\Http\Requests\Rdt\RdtCheckStatusRequest $request
     * @return \App\Http\Resources\RdtApplicantResource
     * @throws \Illuminate\Validation\ValidationException
     */
    public function __invoke(RdtCheckinRequest $request)
    {
        $registrationCode = $request->input('registration_code');

        $eventCode = $request->input('event_code');
        $event     = RdtEvent::where('event_code', $eventCode)->firstOrFail();

        /**
         * @var RdtInvitation $invitation
         */
        $invitation = RdtInvitation::where('registration_code', $registrationCode)
            ->where('rdt_event_id', $event->id)
            ->firstOrFail();

        if ($invitation->attended_at !== null) {
            throw ValidationException::withMessages([
               'registration_code' => ['Already checkin.']
            ]);
        }

        // Make sure lab code sample doesn't duplicate
        $labCodeSampleExisting = RdtInvitation::where('rdt_event_id', $event->id)
            ->where('lab_code_sample', $request->input('lab_code_sample'))
            ->first();

        if ($labCodeSampleExisting !== null) {
            throw ValidationException::withMessages([
                'lab_code_sample' => ['Lab Code Sample already used.']
            ]);
        }

        $invitation->lab_code_sample = $request->input('lab_code_sample');
        $invitation->attend_location = $request->input('location');
        $invitation->attended_at     = now();
        $invitation->save();

        $applicant = $invitation->applicant;

        event(new ApplicantEventCheckin($applicant, $invitation));

        return new RdtApplicantResource($applicant);
    }
}
