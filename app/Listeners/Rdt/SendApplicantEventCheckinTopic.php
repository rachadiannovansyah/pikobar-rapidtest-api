<?php

namespace App\Listeners\Rdt;

use App\Events\Rdt\ApplicantEventCheckin;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendApplicantEventCheckinTopic
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
     * @param ApplicantEventCheckin $event
     * @return void
     */
    public function handle(ApplicantEventCheckin $event)
    {
        //
    }
}
