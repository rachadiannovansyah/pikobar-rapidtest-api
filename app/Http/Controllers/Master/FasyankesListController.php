<?php

namespace App\Http\Controllers\Master;

use App\Http\Resources\FasyankesResource;
use App\Http\Controllers\Controller;
use App\Entities\Fasyankes;
use Illuminate\Http\Request;

class FasyankesListController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($request->name) {
            $fasyankes = Fasyankes::where('name', 'like', "%".$request->name."%")->get();
        } else {
            $fasyankes = Fasyankes::all();
        }
        return FasyankesResource::collection($fasyankes);
    }
}
