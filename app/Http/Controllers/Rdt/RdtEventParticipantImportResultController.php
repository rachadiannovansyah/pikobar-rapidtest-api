<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtEvent;
use App\Entities\RdtInvitation;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rdt\RdtInvitationImportRequest;
use App\Notifications\TestResult;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class RdtEventParticipantImportResultController extends Controller
{

    public function __invoke(RdtInvitationImportRequest $request, RdtEvent $rdtEvent)
    {
        Log::info('IMPORT_TEST_RESULT_START', [
            'file_name' => $request->file('file')->getClientOriginalName(),
        ]);

        $reader = ReaderEntityFactory::createXLSXReader();

        $reader->open($request->file->path());

        $rowsCount = 0;

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $index => $row) {
                $rowArray = $row->toArray();

                if ($index === 1) {
                    continue;
                }

                $registrationCode = $rowArray[0];
                $result           = strtoupper($rowArray[1]);
                $notify           = strtoupper($rowArray[2]);

                /**
                 * @var RdtInvitation $invitation
                 */
                $invitation = RdtInvitation::where('registration_code', $registrationCode)
                    ->where('rdt_event_id', $rdtEvent->id)
                    ->first();

                // Handling error, skip if not found
                if ($invitation === null) {
                    Log::info('IMPORT_TEST_RESULT_INVITATION_NOTFOUND', [
                        'rdt_event_id' => $rdtEvent->id,
                        'registration_code' => $registrationCode,
                        'result' => $result,
                        'notify' => $notify,
                    ]);

                    continue;
                }

                Log::info('IMPORT_TEST_RESULT_ROW', [
                    'row' => $index,
                    'event' => $rdtEvent,
                    'registration_code' => $registrationCode,
                    'result' => $result,
                    'notify' => $notify,
                    'invitation' => $invitation,
                ]);

                $invitation->lab_result_type = $result;
                $invitation->save();

                if ($notify === 'YES') {
                    $applicant = $invitation->applicant;
                    $applicant->notify(new TestResult());

                    $invitation->notified_result_at = Carbon::now();

                    Log::info('NOTIFY_TEST_RESULT', [
                        'applicant' => $applicant,
                        'invitation' => $invitation,
                        'result' => $invitation->lab_result_type
                    ]);
                }

                $rowsCount++;
            }
        }

        Log::info('IMPORT_TEST_RESULT_SUCCESS', [
            'file_name' => $request->file('file')->getClientOriginalName(),
            'rows_total' => $rowsCount,
        ]);

        return response()->json(['message' => 'OK']);
    }
}
