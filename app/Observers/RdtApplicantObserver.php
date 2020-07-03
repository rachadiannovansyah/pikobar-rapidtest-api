<?php

namespace App\Observers;

use App\Entities\RdtApplicant;
use Illuminate\Support\Facades\DB;
use PragmaRX\Random\Random;

class RdtApplicantObserver
{
    /**
     * Handle the rdt applicant "creating" event.
     *
     * @param  \App\Entities\RdtApplicant  $rdtApplicant
     * @return void
     */
    public function creating(RdtApplicant $rdtApplicant)
    {
        $rdtApplicant->province_code     = '32';
        $rdtApplicant->registration_code = $this->generateUniqueEventCode();
    }

    protected function generateUniqueEventCode()
    {
        while(true) {
            $code = $this->generateCode();

            $doesCodeExist = DB::table('rdt_applicants')
                ->where('registration_code', $code)
                ->exists();

            if (! $doesCodeExist) {
                return $code;
            }
        }
    }

    protected function generateCode()
    {
        $random = new Random();

        return $random->numeric()->size(9)->get();
    }
}
