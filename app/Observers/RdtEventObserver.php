<?php

namespace App\Observers;

use App\Entities\RdtEvent;
use Illuminate\Support\Facades\DB;
use PragmaRX\Random\Random;

class RdtEventObserver
{
    /**
     * Handle the rdt event "creating" event.
     *
     * @param  \App\Entities\RdtEvent  $rdtEvent
     * @return void
     */
    public function creating(RdtEvent $rdtEvent)
    {
        $rdtEvent->event_code = $this->generateUniqueEventCode();
    }

    protected function generateUniqueEventCode()
    {
        while(true) {
            $code = $this->generateCode();

            $doesCodeExist = DB::table('rdt_events')
                ->where('event_code', $code)
                ->exists();

            if (! $doesCodeExist) {
                return $code;
            }
        }
    }

    protected function generateCode()
    {
        $random = new Random();

        return $random->uppercase()->size(6)->get();
    }
}
