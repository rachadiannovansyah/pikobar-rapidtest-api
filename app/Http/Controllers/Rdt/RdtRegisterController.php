<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtApplicant;
use App\Entities\RdtEvent;
use App\Entities\RdtInvitation;
use App\Enums\RdtApplicantStatus;
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
        $applicant         = new RdtApplicant();
        $applicant->status = RdtApplicantStatus::NEW();
        $applicant->fill($request->all());
        $applicant->save();

        Log::info('APPLICANT_REGISTER', [
            'id'                => $applicant->id,
            'registration_code' => $applicant->registration_code,
        ]);

        // @TODO Temporary register on the spot what the father
        $applicant->status = RdtApplicantStatus::APPROVED();
        $applicant->save();

        $event = RdtEvent::find(256);

        $invitation = new RdtInvitation();
        $invitation->registration_code = $applicant->registration_code;
        $invitation->event()->associate($event);
        $invitation->save();

        $applicant->invitations()->save($invitation);

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
