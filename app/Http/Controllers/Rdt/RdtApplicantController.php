<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtApplicant;
use App\Enums\RdtApplicantStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rdt\RdtApplicantRequest;
use Illuminate\Http\Response;

class RdtApplicantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        dd('hello');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  RdtApplicantRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RdtApplicantRequest $request)
    {
        $applicant         = new RdtApplicant();
        $applicant->status = RdtApplicantStatus::NEW();
        $applicant->fill($request->all());
        $applicant->save();

        return response()->json([
            'success' => 'successful create Rdt applicant'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param RdtApplicant $rdtApplicant
     * @return Response
     */
    public function show(RdtApplicant $rdtApplicant)
    {
        return response()->json([
            'data' => $rdtApplicant
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RdtApplicantRequest $request
     * @param RdtApplicant $rdtApplicant
     * @return Response
     */
    public function update(RdtApplicantRequest $request, RdtApplicant $rdtApplicant)
    {
        $rdtApplicant->fill($request->all());
        $rdtApplicant->save();

        return response()->json([
            'success' => 'successful updated RDT applicant'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  RdtApplicant  $rdtApplicant
     * @return \Illuminate\Http\Response
     */
    public function destroy(RdtApplicant $rdtApplicant)
    {

        $rdtApplicant->delete();

        return response()->json([
            'success' => 'successful deleted RDT applicant'
        ]);
    }
}
