<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtApplicant;
use App\Enums\RdtApplicantStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rdt\RdtApplicantStoreRequest;
use App\Http\Requests\Rdt\RdtApplicantUpdateRequest;
use App\Http\Resources\RdtApplicantResource;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class RdtApplicantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $status = $request->input('status', 'new');
        $search = $request->input('search');
        $sessionId = $request->input('session_id');
        $cityCode = $request->input('city_code');
        $userCityCode = $request->user()->city_code;
        $registrationDateStart = $request->input('registration_date_start');
        $registrationDateEnd = $request->input('registration_date_end');
        $personStatus = $request->input('person_status');
        $perPage = $this->getPaginationSize($perPage);

        if (
            in_array($sortBy, [
                'id', 'name', 'gender', 'age', 'person_status', 'created_at', 'updated_at', 'registration_at',
            ]) === false
        ) {
            $sortBy = 'name';
        }

        if ($sortBy === 'age') {
            $sortBy = 'birth_date';
        }

        $status === 'new' ? $statusEnum = RdtApplicantStatus::NEW() : $statusEnum = RdtApplicantStatus::APPROVED();

        $records = RdtApplicant::query();

        $records = $this->filterList($search, $records);

        $records->when($registrationDateStart, function ($query, $registrationDateStart, $registrationDateEnd) {
            return $query->whereBetween(DB::raw('CAST(registration_at AS DATE)'), [
                $registrationDateStart, $registrationDateEnd,
            ]);
        });

        $records->when($personStatus, function ($query, $personStatus) {
            return $query->where('person_status', $personStatus);
        });

        $records->when($cityCode, function ($query, $cityCode) {
            return $query->where('city_code', $cityCode);
        });

        $records->when($userCityCode, function ($query, $userCityCode) {
            return $query->where('city_code', $userCityCode);
        });

        $records->when($status, function ($query, $statusEnum) {
            return $query->whereEnum('status', $statusEnum);
        });

        $records->orderBy($sortBy, $sortOrder);
        $records->with(['invitations', 'invitations.event', 'city', 'district', 'village']);

        $records->when($sessionId, function ($query, $sessionId) {
            return $query->where('pikobar_session_id', $sessionId);
        });

        if (strtoupper($perPage) === 'ALL') {
            return RdtApplicantResource::collection($records->get());
        }

        return RdtApplicantResource::collection($records->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RdtApplicantStoreRequest $request
     * @return \App\Http\Resources\RdtApplicantResource
     */
    public function store(RdtApplicantStoreRequest $request)
    {
        $rdtApplicant = RdtApplicant::create($request->all() + [
            'status' => $request->input('status'),
            'registration_at' => Carbon::now(),
            'province_code' => '32',
        ]);

        return new RdtApplicantResource($rdtApplicant);
    }

    /**
     * Display the specified resource.
     *
     * @param RdtApplicant $rdtApplicant
     * @return \App\Http\Resources\RdtApplicantResource
     */
    public function show(RdtApplicant $rdtApplicant)
    {
        $rdtApplicant->load(['invitations' => function ($query) {
            $query->has('event');
        }, 'invitations.event', 'city', 'district', 'village']);
        return new RdtApplicantResource($rdtApplicant);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Rdt\RdtApplicantUpdateRequest $request
     * @param RdtApplicant $rdtApplicant
     * @return \App\Http\Resources\RdtApplicantResource
     */
    public function update(RdtApplicantUpdateRequest $request, RdtApplicant $rdtApplicant)
    {
        $rdtApplicant->update($request->all());

        return new RdtApplicantResource($rdtApplicant);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param RdtApplicant $rdtApplicant
     * @return \Illuminate\Http\Response
     */
    public function destroy(RdtApplicant $rdtApplicant)
    {
        $rdtApplicant->delete();

        return response()->json(['message' => 'DELETED']);
    }

    protected function getPaginationSize($perPage)
    {
        $perPageAllowed = [50, 100, 500];

        if (in_array($perPage, $perPageAllowed)) {
            return $perPage;
        }
        return 15;
    }

    protected function filterList($search, $records)
    {
        if ($search) {
            $records->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('registration_code', 'like', '%' . $search . '%')
                    ->orWhere('workplace_name', 'like', '%' . $search . '%')
                    ->orWhere('nik', $search)
                    ->orWhere('pikobar_session_id', $search)
                    ->orWhere('phone_number', 'like', '%' . $search . '%');
            });
        }

        return $records;
    }
}
