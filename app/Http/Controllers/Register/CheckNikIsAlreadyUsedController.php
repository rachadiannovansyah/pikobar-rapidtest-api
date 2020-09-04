<?php

namespace App\Http\Controllers\Register;

use App\Http\Controllers\Controller;
use App\Http\Requests\Register\CheckNikIsAlreadyRequest;

class CheckNikIsAlreadyUsedController extends Controller
{
    public function __invoke(CheckNikIsAlreadyRequest $request)
    {
        return response()->json(['message' => 'nik belum digunakan']);
    }
}
