<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtEvent;
use App\Enums\RdtEventStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rdt\RdtEventRequest;
use Illuminate\Http\Request;

class RdtEventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
     * @param  \App\RdtEvent  $rdtTestEvent
     * @return \Illuminate\Http\Response
     */
    public function show(RdtEvent $rdtTestEvent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Rdt\RdtEventRequestt  $request
     * @param  \App\RdtEvent  $rdtTestEvent
     * @return \Illuminate\Http\Response
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
     * @param  \App\RdtEvent  $rdtTestEvent
     * @return \Illuminate\Http\Response
     */
    public function destroy(RdtEvent $rdtTestEvent)
    {
        //
    }
}
