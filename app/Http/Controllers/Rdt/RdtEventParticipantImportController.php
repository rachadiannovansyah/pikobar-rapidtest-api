<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtApplicant;
use App\Entities\RdtEvent;
use App\Entities\RdtInvitation;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rdt\RdtInvitationImportRequest;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

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

                    $participant = [
                        'registration_code'     => $rowArray[0],
                        'rdt_event_id'          => $rowArray[1],
                        'rdt_event_schedule_id' => $rowArray[2],
                        'nik'                   => $rowArray[3],
                        'name'                  => $rowArray[4],
                        'city_code'             => (isset($rowArray[5])) ? $rowArray[5]: null
                    ];


                    $applicant = $this->fillApplicant($participant);

                    $this->fillInvitation($applicant, $participant);

                }


            }
        }

        $reader->close();

        return response()->json(['message' => 'import success, '. $count .' rows']);

    }

    private function fillApplicant( array $participant )
    {

        $applicant = RdtApplicant::firstOrCreate([
            'registration_code' => $participant['registration_code'],
            'rdt_event_id'      => $participant['rdt_event_id'],
            'nik'               => $participant['nik'],
            'name'              => $participant['name'],
            'city_code'         => $participant['city_code']
        ]);

        $applicant->rdt_event_id = $participant['rdt_event_id'];
        $applicant->save();

        return $applicant;
    }

    private function fillInvitation( $applicant, array $participant  )
    {
        $rdtInvitation = new RdtInvitation();
        $rdtInvitation->rdt_applicant_id = $applicant->id;
        $rdtInvitation->rdt_event_id = $participant['rdt_event_id'];
        $rdtInvitation->rdt_event_schedule_id = $participant['rdt_event_schedule_id'];
        $rdtInvitation->registration_code = $applicant->registration_code;
        $rdtInvitation->save();

    }
}
