<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtEvent;
use App\Http\Controllers\Controller;
use App\Notifications\RdtEventInvitation;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
            $invitation->notified_result_at = Carbon::today();
        }

        return response()->json(['message' => 'OK']);
    }
}
