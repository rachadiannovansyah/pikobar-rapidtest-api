<?php

namespace App\Http\Controllers\Register;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CheckNikIsAlreadyUsedController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'nik' => 'required|unique:rdt_applicants'
        ]);

        return response()->json(['message' => 'nik belum digunakan']);
    }
}
