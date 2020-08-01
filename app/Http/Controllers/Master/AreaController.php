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
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $records = Area::query();

        if ($request->has('depth')) {
            $records = $records->where('depth', $request->input('depth'));
        }

        if ($request->has('parent_code_kemendagri')) {
            $records = $records->where('parent_code_kemendagri', $request->input('parent_code_kemendagri'));
        } else {
            $records = $records->where('parent_code_kemendagri', '32');
        }

        $records->orderBy('name');

        return AreaResource::collection($records->get());
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
