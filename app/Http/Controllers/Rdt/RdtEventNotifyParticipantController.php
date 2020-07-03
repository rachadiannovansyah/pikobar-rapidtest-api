<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RdtEventNotifyParticipantController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entities\RdtEvent  $rdtEvent
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request, RdtEvent $rdtEvent)
    {
        $notifyTarget  = $request->input('target', 'ALL');
        $notifyMethod  = $request->input('method', 'BOTH');
        $invitationIds = $request->input('invitations_ids');

        $invitations = $rdtEvent->invitations;

        if ($notifyTarget === 'SELECTED') {
            $invitations = $rdtEvent->invitations()->whereIn('id', $invitationIds)->get();
        }

        $invitations->load(['applicant']);

        foreach ($invitations as $invitation) {
            $applicant = $invitation->applicant;

            // @TODO push AWS SQS Queue

            Log::info('EVENT_NOTIFY_PARTICIPANT', [
                'event_id'                    => $rdtEvent->id, 'invitation_id' => $invitation->id,
                'applicant_id'                => $applicant->id,
                'applicant_registration_code' => $applicant->registration_code,
            ]);
        }

        return response()->json(['message' => 'OK']);
    }
}
