<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtApplicant;
use App\Entities\RdtEvent;
use App\Entities\RdtInvitation;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rdt\RdtInvitationImportRequest;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Illuminate\Support\Carbon;

class RdtEventParticipantImportController extends Controller
{
    public function __invoke(RdtInvitationImportRequest $request, RdtEvent $event)
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
                    $eventId = $event->id;
                    $eventScheduleId = $rowArray[2];
                    $nik = $rowArray[3];
                    $name = $rowArray[4];

                    $applicant = $this->fillApplicant($registrationCode, $eventId, $nik, $name);

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

    private function fillApplicant( $registrationCode, $eventId, $nik, $name)
    {
        $applicant = RdtApplicant::firstOrCreate([
            'rdt_event_id' => $eventId,
            'nik' => $nik,
            'name' => $name
        ]);

        $applicant->rdt_event_id = $eventId;
        $applicant->save();

        return $applicant;
    }
}
