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
        $prefixAreaCode = str_replace('.', '', $rdtApplicant->city_code);

        $rdtApplicant->registration_code = $this->generateUniqueCode($prefixAreaCode);
    }

    protected function generateUniqueCode($prefixAreaCode)
    {
        while(true) {
            $code = $prefixAreaCode . $this->generateCode();

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

        return $random->numeric()->size(5)->get();
    }
}
