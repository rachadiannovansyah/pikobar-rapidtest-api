<?php

namespace App\Http\Controllers\Rdt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Entities\RdtInvitation;
use App\Http\Resources\RdtInvitationResource;

class RdtEventParticipantDetailController extends Controller
{
    public function __invoke($id)
    {
        $invitation = RdtInvitation::with('applicant')->findOrFail($id);
        return new RdtInvitationResource($invitation);
    }
}
