<?php

namespace App\Http\Controllers\Rdt;

use Illuminate\Support\Facades\URL;
use UrlSigner;
use QrCode;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RdtQrCodeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $url = URL::route(
            'registration.qrcode',
            $request->query->all()
        );

        if (! UrlSigner::validate($url)) {
            abort(401);
        }

        $code = $request->input('registration_code');

        $output = QrCode::errorCorrection('H')
            ->format('png')
            ->merge('/storage/pikobar.png')
            ->margin(0)
            ->size(1024)
            ->generate($code);

        return response($output, 200, ['Content-Type' => 'image/png']);
    }
}
