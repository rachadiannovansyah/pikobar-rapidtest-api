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
                    $eventId = $event->id;
                    $registrationCode = $rowArray[1];
                    $eventScheduleId = $rowArray[2];
                    $nik = $rowArray[3];
                    $name = $rowArray[4];
                    $cityCode = (isset($rowArray[5])) ? $rowArray[5]: null;

                    $applicant = $this->fillApplicant(
                        $registrationCode,
                        $eventId,
                        $nik,
                        $name,
                        $cityCode
                    );

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

    private function fillApplicant( $registrationCode, $eventId, $nik, $name, $cityCode)
    {
        $applicant = RdtApplicant::firstOrCreate([
            'registration_code' => $registrationCode,
            'rdt_event_id'      => $eventId,
            'nik'               => $nik,
            'name'              => $name,
            'city_code'         => $cityCode
        ]);

        $applicant->rdt_event_id = $eventId;
        $applicant->save();

        return $applicant;
    }
}
