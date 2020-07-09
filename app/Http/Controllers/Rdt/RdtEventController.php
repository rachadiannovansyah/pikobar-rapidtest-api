<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtEvent;
use App\Entities\RdtEventSchedule;
use App\Enums\RdtEventStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rdt\RdtEventStoreRequest;
use App\Http\Requests\Rdt\RdtEventUpdateRequest;
use App\Http\Resources\RdtEventResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RdtEventController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $perPage   = $request->input('per_page', 15);
        $sortBy    = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $status    = $request->input('status', 'draft');
        $search    = $request->input('search');

        if ($perPage > 20) {
            $perPage = 15;
        }

        if (in_array($sortBy, ['event_name', 'start_at', 'end_at', 'status', 'created_at']) === false) {
            $sortBy = 'event_name';
        }

        $statusEnum = 'draft';

        if ($status === 'draft') {
            $statusEnum = RdtEventStatus::DRAFT();
        }

        if ($status === 'published') {
            $statusEnum = RdtEventStatus::PUBLISHED();
        }

        $records = RdtEvent::query();

        if ($search) {
            $records->where(function ($query) use ($search) {
                $query->where('event_name', 'like', '%'.$search.'%');
            });
        }

        $records->whereEnum('status', $statusEnum);
        $records->orderBy($sortBy, $sortOrder);
        $records->with(['city']);
        $records->withCount(['invitations', 'schedules']);

        return RdtEventResource::collection($records->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Rdt\RdtEventStoreRequest  $request
     * @return \App\Http\Resources\RdtEventResource
     */
    public function store(RdtEventStoreRequest $request)
    {
        $rdtEvent = new RdtEvent();
        $rdtEvent->fill($request->all());
        $rdtEvent->save();

        $inputSchedules = $request->input('schedules');

        foreach ($inputSchedules as $inputSchedule) {
            $schedule           = new RdtEventSchedule();
            $schedule->start_at = $inputSchedule['start_at'];
            $schedule->end_at   = $inputSchedule['end_at'];
            $rdtEvent->schedules()->save($schedule);
        }

        $rdtEvent->load('city');

        return new RdtEventResource($rdtEvent);
    }

    /**
     * Display the specified resource.
     *
     * @param  RdtEvent  $rdtEvent
     * @return RdtEventResource
     */
    public function show(RdtEvent $rdtEvent)
    {
        $rdtEvent->loadCount(['invitations', 'schedules']);
        $rdtEvent->load(['schedules', 'city']);

        return new RdtEventResource($rdtEvent);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  RdtEventUpdateRequest  $request
     * @param  RdtEvent  $rdtEvent
     * @return \App\Http\Resources\RdtEventResource
     */
    public function update(RdtEventUpdateRequest $request, RdtEvent $rdtEvent)
    {
        $rdtEvent->fill($request->all());
        $rdtEvent->save();

        foreach ($request->input('schedules') as $schedule) {
            RdtEventSchedule::find($schedule['id'])
                ->update([
                    'start_at' => $schedule['start_at'],
                    'end_at'   => $schedule['end_at']
                ]);
        }

        $rdtEvent->load('city');

        return new RdtEventResource($rdtEvent);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  RdtEvent  $rdtEvent
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(RdtEvent $rdtEvent)
    {
        $rdtEvent->delete();

        return response()->json(['message' => 'DELETED']);
    }
}
