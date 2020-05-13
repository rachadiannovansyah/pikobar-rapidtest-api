<?php

namespace App\Http\Controllers\Master;

use App\Entities\Area;
use App\Http\Controllers\ApiController;
use App\Http\Resources\AreaResource as AreaResource;
use Illuminate\Http\Request;

class AreaController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $areas = Area::query();

        if ($request->has('depth')) {
            $areas = $areas->where('depth', $request->input('depth'));
        }

        if ($request->has('parent_id')) {
            $areas = $areas->where('id', $request->input('parent_id'));
        } elseif ($request->has('parent_code_kemendagri')) {
            $areas = $areas->where('code_kemendagri', $request->input('parent_code_kemendagri'));
        } elseif ($request->has('parent_code_bps')) {
            $areas = $areas->where('code_bps', $request->input('parent_code_bps'));
            // if no parent_id, code_kemendagri, or bps defined, default to
            // parent_id ='1' (jawa barat)
        } else {
            $areas = $areas->where('id', 1);
        }

        return AreaResource::collection($areas->first()->children);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Area $area)
    {
        return new AreaResource($area);
    }

}
