<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtApplicant;
use App\Enums\RdtApplicantStatus;
use App\Events\Rdt\ApplicantRegistered;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rdt\RdtRegisterRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use UrlSigner;

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
        Log::info('APPLICANT_REGISTER_REQUEST', $request->all());

        $applicant         = new RdtApplicant();
        $applicant->status = RdtApplicantStatus::NEW();
        $applicant->fill($request->all());
        $applicant->save();

        event(new ApplicantRegistered($applicant));

        $url = URL::route(
            'registration.download',
            ['registration_code' => $applicant->registration_code]
        );

        return response()->json([
            'registration_code' => $applicant->registration_code,
            'download_url'      => UrlSigner::sign($url),
        ]);
    }
}
