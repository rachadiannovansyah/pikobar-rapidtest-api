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
        $search            = $request->input('name');
        if ($search) {
            $fasyankes = Fasyankes::where('name', 'like', '%' . $search . '%')->get();
        } else {
            $fasyankes = Fasyankes::all();
        }
        return FasyankesResource::collection($fasyankes);
    }
}
