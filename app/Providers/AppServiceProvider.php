<?php

namespace App\Providers;

use App\Entities\RdtInvitation;
use App\Oauth\CustomKeycloakProvider;
use App\Observers\RdtApplicantObserver;
use App\Observers\RdtEventObserver;
use App\Entities\RdtApplicant;
use App\Entities\RdtEvent;
use App\Observers\RdtInvitationObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        $this->bootKeycloakSocialite();

        RdtEvent::observe(RdtEventObserver::class);
        RdtApplicant::observe(RdtApplicantObserver::class);
        RdtInvitation::observe(RdtInvitationObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * bootKeycloakSocialite func
     *
     */
    private function bootKeycloakSocialite()
    {
        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
        $socialite->extend(
            'keycloak',
            function ($app) use ($socialite) {
                $config = $app['config']['services.keycloak'];
                return new CustomKeycloakProvider($config);
            }
        );
    }
}
