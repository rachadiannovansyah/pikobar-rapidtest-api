<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtApplicant;
use App\Enums\RdtApplicantStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rdt\RdtApplicantStoreRequest;
use App\Http\Requests\Rdt\RdtApplicantUpdateRequest;
use App\Http\Resources\RdtApplicantResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RdtApplicantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $perPage    = $request->input('per_page', 15);
        $sortBy     = $request->input('sort_by', 'name');
        $sortOrder  = $request->input('sort_order', 'asc');

        if ($perPage > 100) {
            $perPage = 15;
        }

        if (in_array($sortBy, ['name', 'created_at']) === false) {
            $sortBy = 'name';
        }

        $records = RdtApplicant::query();
        $records->whereEnum('status', RdtApplicantStatus::NEW());
        $records->orderBy($sortBy, $sortOrder);

        return RdtApplicantResource::collection($records->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  RdtApplicantStoreRequest  $request
     * @return \App\Http\Resources\RdtApplicantResource
     */
    public function store(RdtApplicantStoreRequest $request)
    {
        $rdtApplicant         = new RdtApplicant();
        $rdtApplicant->status = RdtApplicantStatus::NEW();
        $rdtApplicant->fill($request->all());
        $rdtApplicant->save();

        return new RdtApplicantResource($rdtApplicant);
    }

    /**
     * Display the specified resource.
     *
     * @param  RdtApplicant  $rdtApplicant
     * @return \App\Http\Resources\RdtApplicantResource
     */
    public function show(RdtApplicant $rdtApplicant)
    {
        return new RdtApplicantResource($rdtApplicant);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Rdt\RdtApplicantUpdateRequest  $request
     * @param  RdtApplicant  $rdtApplicant
     * @return \App\Http\Resources\RdtApplicantResource
     */
    public function update(RdtApplicantUpdateRequest $request, RdtApplicant $rdtApplicant)
    {
        $rdtApplicant->fill($request->all());
        $rdtApplicant->save();

        return new RdtApplicantResource($rdtApplicant);
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

        return response()->json(['message' => 'DELETED']);
    }
}
