<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rdt\RdtEventCheckRequest;
use App\Http\Resources\RdtEventResource;
use Illuminate\Http\Request;

class RdtEventCheckController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \App\Http\Requests\Rdt\RdtEventCheckRequest  $request
     * @return \App\Http\Resources\RdtEventResource
     */
    public function __invoke(RdtEventCheckRequest $request)
    {
        $eventCode = $request->input('event_code');

        $event = RdtEvent::where('event_code', $eventCode)
            ->with(['applicants' => function ($query) {
                $query->orderBy('name');
            }])
            ->firstOrFail();

        return new RdtEventResource($event);
    }
}
