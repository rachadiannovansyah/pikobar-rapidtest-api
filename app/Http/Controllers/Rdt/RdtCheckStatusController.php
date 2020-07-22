<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtApplicant;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rdt\RdtCheckStatusRequest;
use App\Http\Resources\RdtApplicantResource;

class RdtCheckStatusController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \App\Http\Requests\Rdt\RdtCheckStatusRequest  $request
     * @return \App\Http\Resources\RdtApplicantResource
     */
    public function __invoke(RdtCheckStatusRequest $request)
    {
        $registrationCode = $request->input('registration_code');

        $applicant = RdtApplicant::where('registration_code', $registrationCode)
            ->with(['invitations' => function ($query) {
                $query->orderBy('created_at', 'asc');
            }, 'invitations.event'])
            ->firstOrFail();

        return new RdtApplicantResource($applicant);
    }
}
