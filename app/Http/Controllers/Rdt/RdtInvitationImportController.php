<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtApplicant;
use App\Entities\RdtInvitation;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rdt\RdtInvitationImportRequest;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class RdtInvitationImportController extends Controller
{
    public function __invoke(RdtInvitationImportRequest $request)
    {
        $reader = ReaderEntityFactory::createXLSXReader();

        $reader->open($request->file->path());

        $count = 0;

        foreach ($reader->getSheetIterator() as $sheet) {

            foreach ($sheet->getRowIterator() as $key => $row) {

                $rowArray = $row->toArray();

                if ($key > 1 ) {
                    $count++;
                    $registrationCode = $rowArray[0];
                    $eventId = $rowArray[1];
                    $eventScheduleId = $rowArray[2];
                    $nik = $rowArray[3];
                    $name = $rowArray[4];
                    $now = Carbon::now();

                    $applicant = $this->fillApplicant($registrationCode, $eventId, $nik,$name, $now);

                    $rdtInvitation = new RdtInvitation();
                    $rdtInvitation->rdt_applicant_id = $applicant->id;
                    $rdtInvitation->rdt_event_id = $eventId;
                    $rdtInvitation->rdt_event_schedule_id = $eventScheduleId;
                    $rdtInvitation->registration_code = $applicant->registration_code;
                    $rdtInvitation->save();

                }


            }
        }

        $reader->close();

        return response()->json(['message' => 'import success, '. $count .' rows']);

    }

    private function fillApplicant( $registrationCode, $eventId, $nik, $name, $now)
    {
        $applicant = null;

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

        return $applicant;
    }
}
