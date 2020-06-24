<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtEvent;
use App\Enums\RdtEventStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rdt\RdtEventRequest;
use App\Http\Resources\RdtEventResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RdtEventController extends Controller
{
    const DEFAULT_PAGE = 15;

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return RdtEventResource
     */
    public function index(Request $request)
    {
        $perPage = ( empty($request->perPage) ) ? self::DEFAULT_PAGE : $request->perPage;

        return RdtEventResource::collection(RdtEvent::paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RdtEventRequest $request
     * @return JsonResponse
     */
    public function store(RdtEventRequest $request)
    {

        $rdtEventStatus = [
            'published' => RdtEventStatus::PUBLISHED(),
            'draft'     => RdtEventStatus::DRAFT()
        ];

        $rdt = new RdtEvent();
        $rdt->status = $rdtEventStatus[$request->status];
        $rdt->fill($request->except('status'));
        $rdt->save();

        return response()
            ->json(['success' => 'event success created']);
    }

    /**
     * Display the specified resource.
     *
     * @param RdtEvent $rdtEvent
     * @return RdtEventResource
     */
    public function show(RdtEvent $rdtEvent)
    {
        return new RdtEventResource($rdtEvent);
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
     * @param RdtEvent $rdtEvent
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(RdtEvent $rdtEvent)
    {
        $rdtEvent->delete();

        return response()
            ->json(['success' => 'success deleted event']);
    }
}
