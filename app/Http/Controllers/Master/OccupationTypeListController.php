<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Entities\OccupationType;
use App\Http\Resources\OccupationTypeResource;

class OccupationTypeListController extends Controller
{
    public function __invoke()
    {
        return OccupationTypeResource::collection(OccupationType::orderBy('sequence')->get());
    }
}
