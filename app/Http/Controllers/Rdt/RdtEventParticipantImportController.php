<?php

namespace App\Http\Controllers\Rdt;

use App\Channels\SmsChannel;
use App\Channels\WhatsappChannel;
use App\Entities\RdtApplicant;
use App\Entities\RdtEvent;
use App\Entities\RdtInvitation;
use App\Enums\RdtApplicantStatus;
use App\Enums\RdtEventStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rdt\RdtInvitationImportRequest;
use App\Notifications\RdtEventInvitation;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Carbon\Carbon;

class RdtEventParticipantImportController extends Controller
{
    const NOTIFY_SMS = 'sms';

    const NOTIFY_WA = 'wa';

    const NOTIFY_BOTH = 'both';

    public function __invoke(RdtInvitationImportRequest $request, RdtEvent $rdtEvent)
    {

        $count = 0;
        $rdtEvent->status = RdtEventStatus::PUBLISHED();
        $rdtEvent->save();

        $reader = ReaderEntityFactory::createXLSXReader();
        $reader->open($request->file->path());

        foreach ($reader->getSheetIterator() as $sheet) {

            foreach ($sheet->getRowIterator() as $key => $row) {

                $rowArray = $row->toArray();

                if ($key > 1) {

                    $count++;
                    $fileImport = [
                        'registration_code'     => $rowArray[0],
                        'rdt_event_id'          => $rowArray[1],
                        'rdt_event_schedule_id' => $rowArray[2],
                        'nik'                   => $rowArray[3],
                        'name'                  => $rowArray[4],
                        'city_code'             => $rowArray[5],
                        'phone_number'          => $rowArray[6],
                        'notify'                => $rowArray[7],
                        'notify_method'         => $rowArray[8] // SMS/WA/BOTH
                    ];

                    $applicant  = $this->fillApplicant($fileImport);
                    $invitation = $this->fillInvitation($applicant, $fileImport);

                    if (strtolower($fileImport['notify']) === 'yes') {
                        $this->pushNotification($fileImport, $rdtEvent, $applicant);
                        $invitation->notified_at = Carbon::now();
                        $invitation->save();
                    }
                }
            }
        }

        $reader->close();

        return response()->json(['message' => 'import success, ' . $count . ' rows']);
    }

    private function fillApplicant(array $participant)
    {

        $applicant = RdtApplicant::firstOrCreate(
            [ 'registration_code' => $participant['registration_code']],
            [ 'rdt_event_id'      => $participant['rdt_event_id'],
              'nik'               => $participant['nik'],
              'name'              => $participant['name'],
              'city_code'         => $participant['city_code'],
              'phone_number'      => $participant['phone_number']
            ]
        );

        $applicant->rdt_event_id = $participant['rdt_event_id'];
        $applicant->status       = RdtApplicantStatus::APPROVED();
        $applicant->save();

        return $applicant;
    }

    private function fillInvitation($applicant, array $participant)
    {
        $rdtInvitation = new RdtInvitation();
        $rdtInvitation->rdt_applicant_id = $applicant->id;
        $rdtInvitation->rdt_event_id = $participant['rdt_event_id'];
        $rdtInvitation->rdt_event_schedule_id = $participant['rdt_event_schedule_id'];
        $rdtInvitation->registration_code = $applicant->registration_code;
        $rdtInvitation->save();

        return $rdtInvitation;
    }

    private function pushNotification($fileImport, $event, $applicant)
    {
        if (strtolower($fileImport['notify_method']) === self::NOTIFY_WA) {
            $applicant->notifyNow(new RdtEventInvitation($event), [WhatsappChannel::class]);
            dd('wa');
        }

        if (strtolower($fileImport['notify_method']) === self::NOTIFY_SMS) {
            $applicant->notifyNow(new RdtEventInvitation($event), [SmsChannel::class]);
            dd('sms');
        }

        if (strtolower($fileImport['notify_method']) === self::NOTIFY_BOTH) {
            $applicant->notifyNow(new RdtEventInvitation($event), [WhatsappChannel::class, SmsChannel::class]);
        }
    }
}
