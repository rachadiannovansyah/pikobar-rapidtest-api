<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtEvent;
use App\Exports\ParticipantListExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class RdtEventParticipantListExportF2Controller extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(RdtEvent $rdtEvent)
    {
        return Excel::download(new ParticipantListExport($rdtEvent), 'test-excel.xlsx');
    }
}
