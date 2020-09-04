<?php

namespace App\Http\Controllers\Checkin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Checkin\CheckNikIsAlreadyRequest;

class CheckNikIsAlreadyUsedController extends Controller
{
    public function __invoke(CheckNikIsAlreadyRequest $request)
    {
        return response()->json(['message'=>'nik belum digunakan']);
    }
}
