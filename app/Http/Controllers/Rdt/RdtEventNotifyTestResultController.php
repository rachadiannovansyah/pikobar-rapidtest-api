<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtEvent;
use App\Http\Controllers\Controller;
use App\Notifications\RdtEventInvitation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RdtEventNotifyTestResultController extends Controller
{
    public function __invoke(Request $request, RdtEvent $rdtEvent)
    {
        $target        = $request->input('target');
        $invitationIds = $request->input('invitations_ids');
        $invitations   = $rdtEvent->invitations;

        if($target === 'SELECTED'){
            $invitations = $rdtEvent->invitations()->whereIn('id', $invitationIds)->get();
        }

        foreach ($invitations as $invitation){
            $invitation->applicant->notify(new RdtEventInvitation($rdtEvent));
            $invitation->notified_result_at = Carbon::now();

            Log::info('NOTIFY_TEST_RESULT', [
                'applicant' => $invitation->applicant,
                'invitation' => $invitation,
                'result' => $invitation->lab_result_type
            ]);
        }

        return response()->json(['message' => 'OK']);
    }
}
