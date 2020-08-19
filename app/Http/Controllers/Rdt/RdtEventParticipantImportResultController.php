<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtApplicant;
use App\Entities\RdtInvitation;
use App\Http\Controllers\Controller;
use App\Services\Rdt\ReformatPhoneNumber;
use App\Services\Rdt\ResultMessage;
use App\Services\Rdt\SqsMessage;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Illuminate\Http\Request;

class RdtEventParticipantImportResultController extends Controller
{

    private $sqsMessage;

    private $reformatPhoneNumber;

    private $resultMessage;

    public function __construct(
        SqsMessage $sqsMessage,
        ReformatPhoneNumber $reformatPhoneNumber,
        ResultMessage $resultMessage)
    {
        $this->sqsMessage          = $sqsMessage;
        $this->reformatPhoneNumber = $reformatPhoneNumber;
        $this->resultMessage       = $resultMessage;
    }

    public function __invoke(Request  $request)
    {
        $reader = ReaderEntityFactory::createXLSXReader();

        $reader->open($request->file->path());

        foreach ($reader->getSheetIterator() as $sheet) {

            foreach ($sheet->getRowIterator() as $key => $row) {

                $rowArray = $row->toArray();

                if ($key > 1) {

                    $registrationCode = $rowArray[0];
                    $result           = $rowArray[1];

                    $invitation = RdtInvitation::where('registration_code', $registrationCode)->first();
                    $invitation->lab_result_type = $result;
                    $invitation->save();

                    $applicant     = RdtApplicant::find($invitation->rdt_applicant_id);
                    $phoneNumber   = $this->reformatPhoneNumber->reformat($applicant->phone_number);
                    $applicantName = $applicant->name;
                    $messageSms    = $this->resultMessage->messageSms($registrationCode);
                    $messageWa     = $this->resultMessage->messageWa($applicantName, $registrationCode);

                    $this->sqsMessage
                         ->sendMessageToQueue(SqsMessage::SMS_QUEUE_NAME, $phoneNumber, $messageSms);
                    $this->sqsMessage
                         ->sendMessageToQueue(SqsMessage::WA_QUEUE_NAME, $phoneNumber, $messageWa);

                }
            }
        }

        return response()->json(['message' => 'OK']);
    }
}
