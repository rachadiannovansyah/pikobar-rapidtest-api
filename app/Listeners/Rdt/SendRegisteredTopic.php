<?php

namespace App\Listeners\Rdt;

use App\Events\Rdt\ApplicantRegistered;
use AsyncAws\Sns\Input\PublishInput;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendRegisteredTopic
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param ApplicantRegistered $event
     * @return void
     */
    public function handle(ApplicantRegistered $event)
    {
        $aws = app('aws');

        $sns = $aws->sns();

        $sns->publish(new PublishInput([
            'TopicArn' => config('aws.sns.topics.applicant_registered'),
            'Message' => 'New applicant registered',
            'MessageAttributes' => [
                'ID' => [
                    "DataType" => "String",
                    "StringValue" => $event->applicant->id,
                ],
                'RegistrationCode' => [
                    "DataType" => "String",
                    "StringValue" => $event->applicant->registration_code,
                ],
                'PikobarSessionId' => [
                    "DataType" => "String",
                    "StringValue" => $event->applicant->pikobar_session_id ?? 'NONE',
                ],
                'Name' => [
                    "DataType" => "String",
                    "StringValue" => $event->applicant->name,
                ],
                'NIK' => [
                    "DataType" => "String",
                    "StringValue" => $event->applicant->nik,
                ],
                'Gender' => [
                    "DataType" => "String",
                    "StringValue" => $event->applicant->gender,
                ],
                'PhoneNumber' => [
                    "DataType" => "String",
                    "StringValue" => $event->applicant->phone_number,
                ],
                'Address' => [
                    "DataType" => "String",
                    "StringValue" => $event->applicant->address,
                ],
                'AddressCityCode' => [
                    "DataType" => "String",
                    "StringValue" => $event->applicant->city_code,
                ],
                'AddressDistrictCode' => [
                    "DataType" => "String",
                    "StringValue" => $event->applicant->district_code,
                ],
                'AddressVillageCode' => [
                    "DataType" => "String",
                    "StringValue" => $event->applicant->village_code,
                ],
            ]
        ]));
    }
}
