<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtApplicant;
use PDF;
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
        if (! $request->hasValidSignature()) {
            abort(401);
        }

        $registrationCode = $request->input('registration_code');

        $applicant = RdtApplicant::where('registration_code', $registrationCode)->firstOrFail();

        $pdf = PDF::loadView('pdf.applicant', ['applicant' => $applicant]);

        return $pdf->download("BUKTI_PENDAFTARAN_RDT_{$registrationCode}.pdf");
    }
}
