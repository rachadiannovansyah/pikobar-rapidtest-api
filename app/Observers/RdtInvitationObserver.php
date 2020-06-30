<?php

namespace App\Observers;

use App\Entities\RdtInvitation;

class RdtInvitationObserver
{
    /**
     * Handle the rdt invitation "creating" event.
     *
     * @param  \App\Entities\RdtInvitation  $rdtInvitation
     * @return void
     */
    public function creating(RdtInvitation $rdtInvitation)
    {
        $rdtInvitation->registration_code = $rdtInvitation->applicant->registration_code;
    }
}
