<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtEvent;
use App\Enums\RdtEventStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rdt\RdtEventRequest;
use App\Http\Resources\RdtEventResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class RdtEventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RdtEventRequest $request
     * @return JsonResponse
     */
    public function store(RdtEventRequest $request)
    {
        $rdt = new RdtEvent();
        $rdt->status = RdtEventStatus::PUBLISHED();
        $rdt->fill($request->all());
        $rdt->save();

        return response()->json(['success' => 'event success created']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $rdtEventId
     * @return RdtEventResource
     */
    public function show(int $rdtEventId)
    {
        return new RdtEventResource(
            RdtEvent::find($rdtEventId)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RdtEventRequest $request
     * @param RdtEvent $rdtTestEvent
     * @return JsonResponse
     */
    public function update(RdtEventRequest $request, RdtEvent $rdtTestEvent)
    {
        $rdtTestEvent->fill($request->all());
        $rdtTestEvent->save();

        return response()->json(['success' => 'event success updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $rdtEventId
     * @return JsonResponse
     */
    public function destroy($rdtEventId)
    {
        RdtEvent::find($rdtEventId)
            ->delete();

        return response()->json(['success' => 'success deleted event']);
    }
}
