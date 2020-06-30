<?php

namespace App\Http\Controllers\Rdt;

use App\Enums\PersonCaseStatusEnum;
use UrlSigner;
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

        $applicant                = new RdtApplicant();
        $applicant->status        = RdtApplicantStatus::NEW();
        $applicant->person_status = $this->personStatus($request->person_status);
        $applicant->fill($request->all());
        $applicant->save();

        $url = URL::route(
            'registration.download',
            ['registration_code' => $applicant->registration_code]
        );

        return response()->json([
            'registration_code' => $applicant->registration_code,
            'download_url'      => UrlSigner::sign($url)
        ]);
    }

    private function personStatus( $status )
    {
        $personStatus = [
            'odp' => PersonCaseStatusEnum::ODP(),
            'pdp' => PersonCaseStatusEnum::PDP(),
            'otg' => PersonCaseStatusEnum::OTG(),
            'tidak_ketiganya'  => PersonCaseStatusEnum::NOT_ALL(),
            'tidak_tahu'       => PersonCaseStatusEnum::UNKNOWN()
        ];

        if (!$status) {
            return null;
        }
        return $personStatus[$status];
    }
}
