<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtEvent;
use App\Entities\RdtInvitation;
use App\Events\Rdt\ApplicantEventCheckin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rdt\RdtCheckinRequest;
use App\Http\Resources\RdtApplicantResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class RdtCheckinController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \App\Http\Requests\Rdt\RdtCheckStatusRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function __invoke(RdtCheckinRequest $request)
    {
        Log::info('APPLICANT_EVENT_CHECKIN_REQUEST', $request->all());

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
            return response()->json([
                'message' => 'Already checkin.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Make sure lab code sample doesn't duplicate
        $labCodeSampleExisting = RdtInvitation::where('rdt_event_id', $event->id)
            ->where('lab_code_sample', $request->input('lab_code_sample'))
            ->first();

        if ($labCodeSampleExisting !== null) {
            return response()->json([
                'message' => 'Lab Code Sample already used.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
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
