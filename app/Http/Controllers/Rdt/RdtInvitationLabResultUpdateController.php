<?php

namespace App\Http\Controllers\Rdt;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rdt\RdtInvitationLabResultUpdateRequest as Request;
use App\Entities\RdtInvitation;

class RdtInvitationLabResultUpdateController extends Controller
{
    public function __invoke($id, Request $request)
    {
        $rdtInvitation  = RdtInvitation::findOrFail($id);
        $rdtInvitation->update($request->only('lab_result_type'));
        return response()->json(['message' => 'Hasil Pemeriksaan Kode Sampel ' . $rdtInvitation->lab_code_sample . ' Berhasil Di Ubah Menjadi ' . $request->lab_result_type]);
    }
}
