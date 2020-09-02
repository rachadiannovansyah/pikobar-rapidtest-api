<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtEvent;
use App\Entities\RdtInvitation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RdtEventParticipantsController extends Controller
{
    public function __invoke($eventCode, Request $request)
    {
        $per_page  = $request->input('per_page', 50);
        $keyword   = $request->input('keyword');

        $event = RdtEvent::where('event_code', $eventCode)->firstOrFail();
        $invitations = RdtInvitation::leftJoin('rdt_applicants', 'rdt_applicants.id', '=', 'rdt_invitations.rdt_applicant_id')
        ->select('rdt_applicants.name', 'rdt_applicants.registration_code', 'rdt_invitations.lab_code_sample', 'rdt_applicants.created_at', 'rdt_applicants.attended_at')
        ->where('rdt_invitations.rdt_event_id', $event->id);

        if ($keyword) {
            $invitations->where('rdt_applicants.name', 'like', "%$keyword%");
        }

        return response()->json(['data' => $invitations->paginate($per_page)]);
    }
}
