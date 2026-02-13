<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Caso;
use App\Policies\UserPolicy;
use App\Policies\CasoPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
        Caso::class => CasoPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}
