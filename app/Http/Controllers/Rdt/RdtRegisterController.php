<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtApplicant;
use App\Enums\RdtApplicantStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rdt\RdtRegisterRequest;
use Illuminate\Support\Facades\URL;

class RdtRegisterController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \App\Http\Requests\Rdt\RdtRegisterRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(RdtRegisterRequest $request)
    {
        $applicant         = new RdtApplicant();
        $applicant->status = RdtApplicantStatus::NEW();
        $applicant->fill($request->all());
        $applicant->save();

        return response()->json([
            'registration_code' => $applicant->registration_code,
            'download_url'      => URL::temporarySignedRoute(
                'registration.download',
                now()->addMinutes(30),
                ['registration_code' => $applicant->registration_code]
            ),
        ]);
    }
}
