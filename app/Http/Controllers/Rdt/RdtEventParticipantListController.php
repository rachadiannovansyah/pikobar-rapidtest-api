<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\RdtInvitationResource;
use Illuminate\Http\Request;

class RdtEventParticipantListController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function __invoke(Request $request, RdtEvent $rdtEvent)
    {
        $records = $rdtEvent->invitations();

        $records->with(['applicant', 'schedule']);

        return RdtInvitationResource::collection($records->paginate(15));
    }
}
