<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtEvent;
use App\Http\Controllers\Controller;
use App\Services\Rdt\InvitationMessage;
use App\Services\Rdt\ReformatPhoneNumber;
use App\Services\Rdt\SqsMessage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RdtEventNotifyParticipantController extends Controller
{
    private $sqsMessage;

    private $reformatPhoneNumber;

    private $invitationMessage;

    public function __construct(
        SqsMessage $sqsMessage,
        ReformatPhoneNumber $reformatPhoneNumber,
        InvitationMessage  $invitationMessage)
    {
        $this->sqsMessage          = $sqsMessage;
        $this->reformatPhoneNumber = $reformatPhoneNumber;
        $this->invitationMessage   = $invitationMessage;
    }


    public function __invoke(Request $request, RdtEvent $rdtEvent)
    {
        $notifyTarget  = $request->input('target', 'ALL');
        $notifyMethod  = $request->input('method', 'BOTH');
        $invitationIds = $request->input('invitations_ids');

        $invitations = $rdtEvent->invitations;

        if ($notifyTarget === 'SELECTED') {
            $invitations = $rdtEvent->invitations()->whereIn('id', $invitationIds)->get();
        }

        $invitations->load(['applicant']);

        foreach ($invitations as $invitation) {

            $applicant = $invitation->applicant;

            if ( $notifyMethod === 'BOTH') {

                $phoneNumber    = $this->reformatPhoneNumber
                                       ->reformat($applicant->phone_number);
                $messageSms     = $this->invitationMessage
                                       ->messageSms($rdtEvent->host_name, $applicant->registration_code);
                $messageWa      = $this->invitationMessage
                                       ->messageWa($applicant->name, $rdtEvent->host_name, $applicant->registration_code);

                $this->sqsMessage
                     ->sendMessageToQueue(SqsMessage::SMS_QUEUE_NAME, $phoneNumber, $messageSms);
                $this->sqsMessage
                     ->sendMessageToQueue(SqsMessage::WA_QUEUE_NAME, $phoneNumber, $messageWa);

                $invitation->notified_at = Carbon::now();
                $invitation->save();

            }

            Log::info('EVENT_NOTIFY_PARTICIPANT', [
                'event_id'                    => $rdtEvent->id, 'invitation_id' => $invitation->id,
                'applicant_id'                => $applicant->id,
                'applicant_registration_code' => $applicant->registration_code,
            ]);
        }

        return response()->json(['message' => 'OK']);
    }
}
