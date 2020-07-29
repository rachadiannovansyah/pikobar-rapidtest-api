<?php

namespace App\Http\Controllers;

use AsyncAws\Core\AwsClientFactory;
use AsyncAws\Sqs\Input\SendMessageRequest;
use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $factory = new AwsClientFactory([
            'region'            => config('aws.region'),
            'accessKeyId'       => config('aws.key'),
            'accessKeySecret'   => config('aws.secret')
        ]);

        $sqs = $factory->sqs();

        $queueUrl = $sqs->getQueueUrl(['QueueName' => 'smsblast-queue'])->getQueueUrl();

        $messageRequest = new SendMessageRequest([
            'QueueUrl'          => $queueUrl,
            'DelaySeconds'      => 10,
            'MessageAttributes' => [
                'PhoneNumber'   => [
                    'DataType'  => 'String',
                    'StringValue' => '085729402579'
                ]
            ],
            'MessageBody' => 'test',
        ]);

        $sqs->sendMessage($messageRequest);
    }
}
