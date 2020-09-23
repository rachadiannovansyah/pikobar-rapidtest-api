<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Resources\FasyankesResource;
use App\Http\Controllers\Controller;
use App\Entities\Fasyankes;

class FasyankesListController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($request->name) {
            $fasyankes = Fasyankes::where('name', 'like', "%" . $request->name . "%")->get();
        } else {
            $fasyankes = Fasyankes::all();
        }
        return FasyankesResource::collection($fasyankes);
    }
}
