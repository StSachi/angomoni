<?php

namespace App\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Listeners\LogLogin;
use App\Listeners\LogLogout;

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
}
