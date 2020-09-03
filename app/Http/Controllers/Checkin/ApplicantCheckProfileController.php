<?php

namespace App\Http\Controllers\Checkin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rdt\ApplicantsCekProfileRequest;
use App\Entities\RdtApplicant;
use App\Http\Resources\ApplicantProfileResource;

class ApplicantCheckProfileController extends Controller
{
    public function __invoke(ApplicantsCekProfileRequest $request)
    {
        $rdtApplicant = RdtApplicant::where('registration_code', $request->registration_code)->firstOrFail();
        return response()->json(new ApplicantProfileResource($rdtApplicant));
    }
}
