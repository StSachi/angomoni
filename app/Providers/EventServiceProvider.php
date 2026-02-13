<?php

namespace App\Providers;

use App\Listeners\LogLogin;
use App\Listeners\LogLogout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Login::class => [
            LogLogin::class,
        ],
        Logout::class => [
            LogLogout::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}
