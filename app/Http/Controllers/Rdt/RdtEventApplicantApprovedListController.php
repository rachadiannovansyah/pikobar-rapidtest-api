<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtApplicant;
use App\Enums\RdtApplicantStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\RdtApplicantResource;
use Illuminate\Http\Request;

class RdtEventApplicantApprovedListController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $perPage = $request->input('per_page', 15);

        if ($perPage > 100) {
            $perPage = 15;
        }

        $records = RdtApplicant::query();
        $records->whereEnum('status', RdtApplicantStatus::APPROVED());

        return RdtApplicantResource::collection($records->paginate($perPage));
    }
}
