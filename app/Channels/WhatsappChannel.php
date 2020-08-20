<?php

namespace App\Channels;

use AsyncAws\Sqs\Input\SendMessageRequest;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class WhatsappChannel
{
    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @return bool
     * @throws \Exception
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toWhatsapp($notifiable);

        $phoneNumber = $this->getPhoneNumber($notifiable);

        if ($phoneNumber instanceof Collection) {
            $phoneNumber->each(function ($phoneNumberValue) use ($message) {
                $this->pushQueue($phoneNumberValue, $message);
            });

            return true;
        }

        $this->pushQueue($phoneNumber, $message);

        return true;
    }

    protected function cleanPhoneNumber($phoneNumber)
    {
        return preg_replace('/^0{1}/', '62', $phoneNumber);
    }

    protected function pushQueue($phoneNumber, $message)
    {
        /**
         * @var \AsyncAws\Core\AwsClientFactory $aws
         * @var \AsyncAws\Sqs\SqsClient $sqs
         */
        $aws      = app('aws');
        $sqs      = $aws->sqs();
        $queueUrl = $sqs->getQueueUrl(['QueueName' => 'wablast-queue'])->getQueueUrl();

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

        return $sqs->sendMessage($messageRequest);
    }

    /**
     * In development/staging environment, we need to carefully send notifications.
     * Redirect/send to developer instead to real numbers
     * @param $notifiable
     * @return string|\Illuminate\Support\Collection
     * @throws \Exception
     */
    protected function getPhoneNumber($notifiable)
    {
        $currentEnvironment = config('app.env');

        if ($currentEnvironment === 'production') {
            return $this->cleanPhoneNumber($notifiable->phone_number);
        }

        $configPhoneNumbers = config('notifications.notify_to');

        if (empty($configPhoneNumbers)) {
            throw new \Exception("Please update config 'notifications.notify_to'");
        }

        return Str::of($configPhoneNumbers)->explode(',')->map(function ($phoneNumber) {
            return $this->cleanPhoneNumber($phoneNumber);
        });
    }
}
