<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtApplicant;
use App\Entities\RdtEvent;
use App\Entities\RdtInvitation;
use App\Enums\RdtApplicantStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rdt\RdtInvitationImportRequest;
use AsyncAws\Core\AwsClientFactory;
use AsyncAws\Sqs\Input\GetQueueUrlRequest;
use AsyncAws\Sqs\Input\SendMessageRequest;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Carbon\Carbon;


class RdtEventParticipantImportController extends Controller
{

    const SMS_QUEUE_NAME = 'smsblast-queue';

    const WA_QUEUE_NAME = 'wablast-queue';

    const NOTIFY_SMS = 'sms';

    const NOTIFY_WA = 'wa';

    const NOTIFY_BOTH = 'both';

    private $sqs;

    public function __construct()
    {

        // set credential sqs
        $factory = new AwsClientFactory([
            'region'            => config('aws.region'),
            'accessKeyId'       => config('aws.key'),
            'accessKeySecret'   => config('aws.secret')
        ]);

        $this->sqs = $factory->sqs();

    }

    public function __invoke(RdtInvitationImportRequest $request, RdtEvent $rdtEvent)
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
                        'city_code'             => $rowArray[5],
                        'phone_number'          => $rowArray[6],
                        'notify'                => $rowArray[7],
                        'notify_method'         => $rowArray[8] // SMS/WA/BOTH
                    ];


                    $applicant  = $this->fillApplicant($participant);

                    $invitation = $this->fillInvitation($applicant, $participant);

                    if ( strtolower($participant['notify']) === 'yes' ) {

                        $this->pushNotification($participant, $rdtEvent, $applicant);
                        $invitation->notified_at = Carbon::now();
                        $invitation->save();

                    }

                }

            }
        }

        $reader->close();

        return response()->json(['message' => 'import success, '. $count .' rows']);

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
            ]);

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

    private function getQueueUrl($queueName) {

        return $this->sqs->getQueueUrl(new GetQueueUrlRequest([
            'QueueName' => $queueName
        ]))->getQueueUrl();
    }

    private function sendMessageToQueue($queueName, $phoneNumber, $message) {

        $messageRequest = new SendMessageRequest([
            'QueueUrl'          => $this->getQueueUrl($queueName),
            'DelaySeconds'      => 10,
            'MessageAttributes' => [
                'PhoneNumber'   => [
                    'DataType'  => 'String',
                    'StringValue'   => $phoneNumber
                ]
            ],
            'MessageBody'       => $message
        ]);


        $this->sqs->sendMessage($messageRequest);

    }

    private function reformatPhoneNumber($phoneNumber, $format = 'sms')
    {

        if ($format === 'wa') {

            return $this->reformatWa($phoneNumber);

        }

        return $this->reformatSms($phoneNumber);

    }

    private function reformatSms($phoneNumber)
    {
        if ($phoneNumber[0] == '6') {
            return substr_replace($phoneNumber,'0',0, 2);
        }

        if ($phoneNumber[0] == '+') {
            return substr_replace($phoneNumber,'0',0, 3);
        }

        return $phoneNumber;
    }

    private function reformatWa($phoneNumber)
    {
        if ($phoneNumber[0] == '0') {
            return substr_replace($phoneNumber,'62',0, 1);
        }

        if ($phoneNumber[0] == '+') {
            return substr_replace($phoneNumber,'',0, 1);
        }

        return $phoneNumber;
    }

    private function messageWa($name, $hostName, $registrationCode){

        $message  = 'Yth. '.$name.' Sampurasun, Anda diundang untuk melakukan Tes Masif COVID-19 oleh '.$hostName;
        $message .= ' Silakan buka tautan https://s.id/tesmasif2 dan masukkan Nomor Pendaftaran berikut: ';
        $message .= $registrationCode.' untuk melihat undangan. Hatur nuhun';

        return $message;
    }

    private function messageSms($hostName, $registrationCode){

        $message  = 'Sampurasun. Anda diundang Tes Masif COVID-19 ';
        $message .= $hostName .'.Buka tautan s.id/tesmasif1 dan input nomor: ';
        $message .= $registrationCode.' untuk melihat undangan.';

        return $message;
    }

    private function pushNotification($participant, $event, $applicant)
    {
        $phoneNumberWa  = $this->reformatPhoneNumber($participant['phone_number'], self::NOTIFY_WA);
        $phoneNumberSms = $this->reformatPhoneNumber($participant['phone_number'], self::NOTIFY_SMS);
        $messageWa      = $this->messageWa($applicant->name, $event->host_name, $applicant->registration_code);
        $messageSms     = $this->messageSms($event->host_name, $applicant->registration_code);

        if (strtolower($participant['notify_method']) === self::NOTIFY_WA ) {
            $this->sendMessageToQueue(self::WA_QUEUE_NAME, $phoneNumberWa, $messageWa);
        }

        if (strtolower($participant['notify_method']) === self::NOTIFY_SMS) {
            $this->sendMessageToQueue(self::SMS_QUEUE_NAME, $phoneNumberSms, $messageSms);
        }

        if (strtolower($participant['notify_method']) === self::NOTIFY_BOTH) {
            $this->sendMessageToQueue(self::WA_QUEUE_NAME, $phoneNumberWa, $messageWa);
            $this->sendMessageToQueue(self::SMS_QUEUE_NAME, $phoneNumberSms, $messageSms);
        }
    }
}
