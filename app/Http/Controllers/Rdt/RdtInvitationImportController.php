<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtApplicant;
use App\Entities\RdtInvitation;
use App\Http\Controllers\Controller;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class RdtInvitationImportController extends Controller
{
    public function __invoke(Request $request)
    {
        $reader = ReaderEntityFactory::createXLSXReader();

        $reader->open($request->file->path());

        foreach ($reader->getSheetIterator() as $sheet) {

            foreach ($sheet->getRowIterator() as $key => $row) {

                $rowArray = $row->toArray();

                if ($key > 1 ) {

                    $applicant = null;
                    $registrationCode = $rowArray[0];
                    $eventId = $rowArray[1];
                    $nik = $rowArray[3];
                    $name = $rowArray[4];
                    $now = Carbon::now();

                    if (empty($registrationCode)) {
                        $applicant = new RdtApplicant();
                        $applicant->rdt_event_id = $eventId;
                        $applicant->nik = $nik;
                        $applicant->name = $name;
                        $applicant->invited_at = $now;
                        $applicant->save();
                    } else {
                        $applicant = RdtApplicant::where('nik', $nik)->first();
                        $applicant->rdt_event_id = $eventId;
                        $applicant->invited_at = $now;
                        $applicant->save();
                    }

                    $rdtInvitation = new RdtInvitation();
                    $rdtInvitation->rdt_applicant_id = $applicant->id;
                    $rdtInvitation->rdt_event_id = $eventId;
                    $rdtInvitation->registration_code = $applicant->registration_code;
                    $rdtInvitation->save();

                }


            }
        }

        $reader->close();

    }
}
