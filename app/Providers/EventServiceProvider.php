<?php

namespace App\Providers;

use App\Events\Rdt\ApplicantEventCheckin;
use App\Events\Rdt\ApplicantRegistered;
use App\Listeners\Rdt\SendApplicantEventCheckinTopic;
use App\Listeners\Rdt\SendRegisteredTopic;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        ApplicantRegistered::class => [
            SendRegisteredTopic::class,
        ],
        ApplicantEventCheckin::class => [
            SendApplicantEventCheckinTopic::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
