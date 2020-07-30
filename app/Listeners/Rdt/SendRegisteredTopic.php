<?php

namespace App\Listeners\Rdt;

use App\Events\Rdt\ApplicantRegistered;
use AsyncAws\Sns\Input\PublishInput;
use AsyncAws\Sns\SnsClient;
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
            'TopicArn' => '',
            'Message' => 'New applicant registered',
            'MessageAttributes' => [
                'TestAttr' => [
                    "DataType" => "String",
                    "StringValue" => "Test"
                ]
            ]
        ]));
    }
}
