<?php

namespace App\Http\Controllers\Rdt;

use Illuminate\Support\Facades\Log;
use PDF;
use UrlSigner;
use App\Entities\RdtApplicant;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RdtRegisterDownloadController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $url = URL::route(
            'registration.download',
            $request->only(['registration_code', 'expires', 'signature'])
        );

        if (! UrlSigner::validate($url)) {
            abort(401);
        }

        $registrationCode = $request->input('registration_code');

        $applicant = RdtApplicant::where('registration_code', $registrationCode)->firstOrFail();

        Log::info('APPLICANT_REGISTER_DOWNLOAD_PDF', [
            'id'                => $applicant->id,
            'registration_code' => $applicant->registration_code,
        ]);

        $pdf = PDF::loadView('pdf.applicant', ['applicant' => $applicant]);

        return $pdf->download("PIKOBAR_BUKTI_PENDAFTARAN_TESMASIF_{$registrationCode}.pdf");
    }
}
