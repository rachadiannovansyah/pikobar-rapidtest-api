<?php

namespace App\Events\Rdt;

use App\Entities\RdtApplicant;
use App\Notifications\RegisterThankYou;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ApplicantRegistered
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @var RdtApplicant
     */
    public $applicant;

    /**
     * Create a new event instance.
     *
     * @param RdtApplicant $applicant
     */
    public function __construct(RdtApplicant $applicant)
    {
        $this->applicant = $applicant;

        Log::info('APPLICANT_REGISTER', $applicant->toArray());

        $notify = config('notifications.messages.registered_thankyou');

        if ($notify) {
            $applicant->notify(new RegisterThankYou());
        }
    }
}
