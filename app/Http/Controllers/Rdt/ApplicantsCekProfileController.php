<?php

namespace App\Http\Controllers\Rdt;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rdt\ApplicantsCekProfileRequest;
use App\Entities\RdtApplicant;
use App\Http\Resources\ApplicantProfileResource;

class ApplicantsCekProfileController extends Controller
{
    public function __invoke(ApplicantsCekProfileRequest $request)
    {
        $rdtApplicant = RdtApplicant::where('registration_code', $request->registration_code)->firstOrFail();
        $response = new ApplicantProfileResource($rdtApplicant);
        return response()->json($response);
    }
}
