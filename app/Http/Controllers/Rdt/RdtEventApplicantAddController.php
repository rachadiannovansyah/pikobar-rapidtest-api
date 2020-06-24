<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RdtEventApplicantAddController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \App\Entities\RdtEvent  $rdtEvent
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __invoke(RdtEvent $rdtEvent, Request $request)
    {
        //
    }
}
