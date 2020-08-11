<?php

namespace App\Services\Rdt;

use AsyncAws\Core\AwsClientFactory;
use AsyncAws\Sqs\Input\GetQueueUrlRequest;
use AsyncAws\Sqs\Input\SendMessageRequest;

class SqsMessage {

    const SMS_QUEUE_NAME = 'smsblast-queue';

    const WA_QUEUE_NAME = 'wablast-queue';

    private $sqs;

    public function __construct()
    {
        $factory = app('aws');

        $this->sqs = $factory->sqs();
    }

    private function getQueueUrl($queueName)
    {

        return $this->sqs->getQueueUrl(new GetQueueUrlRequest([
            'QueueName' => $queueName
        ]))->getQueueUrl();
    }

    public function sendMessageToQueue($queueName, $phoneNumber, $message)
    {

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
}
