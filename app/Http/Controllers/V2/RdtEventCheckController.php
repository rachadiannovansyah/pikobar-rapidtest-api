<?php

namespace App\Http\Controllers\V2;

use App\Entities\RdtEvent;
use App\Entities\RdtInvitation;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rdt\RdtEventCheckRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\RdtEventInvitationsResource;

class RdtEventCheckController extends Controller
{
    public function __invoke(RdtEventCheckRequest $request)
    {
        Log::info('MOBILE_CHECK_EVENT_REQUEST', $request->all());

        $eventCode = $request->input('event_code');
        $per_page  = $request->input('per_page', 50);
        $keyword   = $request->input('keyword');

        $event = RdtEvent::where('event_code', $eventCode)->firstOrFail();
        $invitations = RdtInvitation::leftJoin('rdt_applicants', 'rdt_applicants.id', '=', 'rdt_invitations.rdt_applicant_id')
        ->select('rdt_applicants.name', 'rdt_applicants.registration_code', 'rdt_invitations.lab_code_sample', 'rdt_applicants.created_at', 'rdt_applicants.attended_at')
        ->where('rdt_invitations.rdt_event_id', $event->id);

        if ($keyword) {
            $invitations->where('rdt_applicants.name', 'like', "%$keyword%");
        }

        // Pastikan tidak bisa checkin setelah tanggal selesai
        // Beri tambahan extra 12 jam
        if ($event->end_at->addHours(12)->isPast()) {
            return $this->responseFailedEventPast($event);
        }

        $record = [
            'event_code'        => $event->event_code,
            'event_name'        => $event->event_name,
            'event_location'    => $event->event_location,
            'start_at'          => $event->start_at,
            'end_at'            => $event->end_at,
            'invitations_count' => $invitations->count(),
            'attendees_count'   => $invitations->whereNotNull('rdt_invitations.attended_at')->count(),
            'invitations'       => $invitations->paginate($per_page)
        ];

        return response()->json(['data' => $record]);
    }

    protected function responseFailedEventPast(RdtEvent $event)
    {
        Log::info('MOBILE_CHECK_EVENT_REQUEST_FAILED_PAST', ['event_code' => $event->event_code]);

        $endAt = $event->end_at->setTimezone('Asia/Jakarta');
        return response()->json([
            'error'   => 'EVENT_PAST',
            'message' => "Kode Event: {$event->event_code} - {$event->event_name} sudah berakhir pada {$endAt}.
            Periksa kembali input Kode Event.",
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
