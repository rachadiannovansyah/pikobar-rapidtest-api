<?php

namespace App\Http\Controllers\Rdt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Entities\RdtInvitation;

class RdtCheckinBulkController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $request->data;
        $successSync = [];
        $failedSync = [];
        foreach ($data as $row) {
            $rdtInvitation = RdtInvitation::where('registration_code', $row['registration_code'])->first();
            if ($rdtInvitation != null || $rdtInvitation->attended_at != null) {
                $rdtInvitation->attended_at = $row['attended_at'];
                $rdtInvitation->save();

                $successSync[] = $row['registration_code'];
            } else {
                $failedSync[] = [
                    'registration_code' =>  $row['registration_code'],
                    'message'           =>  'Sudah Melakukan Checkin'
                ];
            }
        }

        return response()->json(['succes' => $successSync,'failed' => $failedSync]);
    }
}
