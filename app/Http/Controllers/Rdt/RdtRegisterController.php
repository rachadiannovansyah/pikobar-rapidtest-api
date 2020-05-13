<?php

namespace App\Http\Controllers\Rdt;

use App\Enums\RdtApplicantStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rdt\RdtRegisterRequest;
use App\Http\Resources\RdtApplicantResource;
use App\RdtApplicant;

class RdtRegisterController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \App\Http\Requests\Rdt\RdtRegisterRequest  $request
     * @return \App\Http\Resources\RdtApplicantResource
     */
    public function __invoke(RdtRegisterRequest $request)
    {
        $applicant         = new RdtApplicant();
        $applicant->status = RdtApplicantStatus::NEW();
        $applicant->fill($request->all());
        $applicant->save();

        return new RdtApplicantResource($applicant);
    }
}
