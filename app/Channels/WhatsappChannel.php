<?php

namespace App\Channels;

use AsyncAws\Sqs\Input\SendMessageRequest;
use Illuminate\Notifications\Notification;

class WhatsappChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toWhatsapp($notifiable);

        $aws = app('aws');

        $sqs = $aws->sqs();

        $queueUrl = $sqs->getQueueUrl(['QueueName' => 'wablast-queue'])->getQueueUrl();

        $phoneNumber = preg_replace('/^0{1}/', '62', $notifiable->phone_number);

        $messageRequest = new SendMessageRequest([
            'QueueUrl'          => $queueUrl,
            'DelaySeconds'      => 10,
            'MessageAttributes' => [
                'PhoneNumber'   => [
                    'DataType'  => 'String',
                    'StringValue' => $phoneNumber
                ]
            ],
            'MessageBody' => $message,
        ]);

        $sqs->sendMessage($messageRequest);
    }
}
