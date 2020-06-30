<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtEvent;
use App\Entities\RdtInvitation;
use App\Http\Controllers\Controller;
use App\Http\Resources\RdtEventResource;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class RdtEventParticipantAddController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entities\RdtEvent  $rdtEvent
     * @return \App\Http\Resources\RdtEventResource
     */
    public function __invoke(Request $request, RdtEvent $rdtEvent)
    {
        $applicants   = $request->input('applicants');
        $applicantIds = Arr::pluck($applicants, 'rdt_applicant_id');

        foreach ($applicantIds as $applicantId) {
            $invitation = RdtInvitation::firstOrNew(['rdt_applicant_id' => $applicantId]);
            $invitation->event()->associate($rdtEvent);
            $invitation->save();
        }

        $rdtEvent->load('invitations');

        return new RdtEventResource($rdtEvent);
    }
}
