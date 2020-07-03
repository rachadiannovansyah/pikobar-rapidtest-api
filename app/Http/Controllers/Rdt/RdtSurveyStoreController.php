<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtApplicant;
use App\Entities\RdtSurvey;
use App\Http\Controllers\Controller;
use App\Http\Resources\RdtSurveyResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RdtSurveyStoreController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\RdtSurveyResource
     */
    public function __invoke(Request $request)
    {
        $applicant = RdtApplicant::where('registration_code', $request->input('registration_code'))->firstOrFail();

        $record                    = new RdtSurvey();
        $record->registration_code = $request->input('registration_code');
        $record->invited           = $request->input('invited');
        $record->attended          = $request->input('attended');
        $record->interested        = $request->input('interested');
        $record->test_method       = $request->input('test_method');

        $record->applicant()->associate($applicant);
        $record->save();

        Log::info('APPLICANT_SURVEY_STORE', [
            'id'                => $applicant->id,
            'registration_code' => $applicant->registration_code,
        ]);

        return new RdtSurveyResource($record);
    }
}
