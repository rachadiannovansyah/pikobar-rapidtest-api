<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RdtCheckEventController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        $rdtEvent = RdtEvent::where('event_code', $request->input('event_code'))->firstOrFail();

        return response()->json([
           'event_name' => $rdtEvent->event_name,
           'start_at' => $rdtEvent->start_at,
           'end_at' => $rdtEvent->end_at,
        ]);
    }
}
