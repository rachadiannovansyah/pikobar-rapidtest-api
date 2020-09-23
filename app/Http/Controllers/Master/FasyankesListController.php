<?php

namespace App\Http\Controllers\Master;

use App\Http\Resources\FasyankesResource;
use App\Http\Controllers\Controller;
use App\Entities\Fasyankes;

class FasyankesListController extends Controller
{
    public function __invoke(Fasyankes $fasyankes)
    {
        return FasyankesResource::collection(Fasyankes::all());
    }
}
