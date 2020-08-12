<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtApplicant;
use App\Enums\RdtApplicantStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rdt\RdtApplicantStoreRequest;
use App\Http\Requests\Rdt\RdtApplicantUpdateRequest;
use App\Http\Resources\RdtApplicantResource;
use Illuminate\Http\Request;

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
        $perPage           = $request->input('per_page', 15);
        $sortBy            = $request->input('sort_by', 'created_at');
        $sortOrder         = $request->input('sort_order', 'desc');
        $status            = $request->input('status', 'new');
        $search            = $request->input('search');
        $pikobarSessionId  = $request->input('pikobar_session_id');

        if ($perPage > 20) {
            $perPage = 15;
        }

        if (in_array($sortBy, ['id', 'name', 'gender', 'age', 'person_status', 'created_at']) === false) {
            $sortBy = 'name';
        }

        if ($sortBy === 'age') {
            $sortBy = 'birth_date';
        }

        $statusEnum = 'new';

        if ($status === 'new') {
            $statusEnum = RdtApplicantStatus::NEW();
        }

        if ($status === 'approved') {
            $statusEnum = RdtApplicantStatus::APPROVED();
        }

        $records = RdtApplicant::query();

        if ($search) {
            $records->where(function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('registration_code', 'like', '%'.$search.'%')
                    ->orWhere('phone_number', 'like', '%'.$search.'%');
            });
        }

        if ($pikobarSessionId) {
            $records->where('pikobar_session_id', '=', $pikobarSessionId);
        }

        if ($request->has('city_code')) {
            $records->where('city_code', $request->input('city_code'));
        }

        if ($request->user()->city_code) {
            $records->where('city_code', $request->user()->city_code);
        }

        if ($request->has('status')) {
            $records->whereEnum('status', $statusEnum);
        }

        $records->orderBy($sortBy, $sortOrder);
        $records->with(['invitations', 'invitations.event', 'city', 'district', 'village']);

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
        $rdtApplicant->load(['invitations', 'invitations.event', 'city', 'district', 'village']);

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
