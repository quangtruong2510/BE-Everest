<?php

namespace App\Repositories;

use Illuminate\Support\ServiceProvider;

class BackendServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(
            'App\Repositories\UserRepository\UserRepositoryInterface',
            'App\Repositories\UserRepository\UserRepository',
        );

        $this->app->bind(
            'App\Repositories\RefreshTokenRepository\RefreshTokenRepositoryInterface',
            'App\Repositories\RefreshTokenRepository\RefreshTokenRepository'
        );
        $this->app->bind(
            'App\Repositories\CampaignRepository\CampaignRepositoryInterface',
            'App\Repositories\CampaignRepository\CampaignRepository'
        );
    }
}