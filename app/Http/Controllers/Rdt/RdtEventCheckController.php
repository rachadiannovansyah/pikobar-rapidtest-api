<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtEvent;
use App\Entities\RdtInvitation;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rdt\RdtEventCheckRequest;
use Illuminate\Support\Facades\Log;

class RdtEventCheckController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \App\Http\Requests\Rdt\RdtEventCheckRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(RdtEventCheckRequest $request)
    {
        Log::info('MOBILE_CHECK_EVENT_REQUEST', $request->all());

        $eventCode = $request->input('event_code');

        $event = RdtEvent::where('event_code', $eventCode)
            ->with(['invitations'])
            ->withCount(['invitations', 'schedules', 'attendees'])
            ->firstOrFail();

        $record = [
            'event_code'     => $event->event_code,
            'event_name'     => $event->event_name,
            'event_location' => $event->event_location,
            'start_at'       => $event->start_at,
            'end_at'         => $event->end_at,
            'invitations_count' => $event->invitations_count,
            'attendees_count'   => $event->attendees_count,
            'invitations'    => $event->invitations->map(function (RdtInvitation $invitation) {
                // @TODO sort by name
                return [
                    'name'              => $invitation->applicant->name,
                    'registration_code' => $invitation->registration_code,
                    'lab_code_sample'   => $invitation->lab_code_sample,
                    'created_at '       => $invitation->created_at,
                    'attended_at'       => $invitation->attended_at,
                ];
            }),
        ];

        return response()->json(['data' => $record]);
    }
}
