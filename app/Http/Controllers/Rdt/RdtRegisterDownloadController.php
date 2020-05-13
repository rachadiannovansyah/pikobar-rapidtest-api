<?php

namespace App\Http\Controllers\Rdt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RdtRegisterDownloadController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        if (! $request->hasValidSignature()) {
            abort(401);
        }

        return 'OK';
    }
}
