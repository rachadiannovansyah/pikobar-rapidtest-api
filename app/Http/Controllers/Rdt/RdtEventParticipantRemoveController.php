<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\RdtEventResource;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class RdtEventParticipantRemoveController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Entities\RdtEvent $rdtEvent
     * @return \App\Http\Resources\RdtEventResource
     */
    public function __invoke(Request $request, RdtEvent $rdtEvent)
    {
        $applicants   = $request->input('applicants');
        $applicantIds = Arr::pluck($applicants, 'rdt_applicant_id');

        $rdtEvent->invitations()
            ->where('rdt_event_id', $rdtEvent->id)
            ->whereIn('rdt_applicant_id', $applicantIds)->delete();

        $rdtEvent->load('invitations');

        return new RdtEventResource($rdtEvent);
    }
}
