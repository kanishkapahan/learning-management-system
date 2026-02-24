<?php

namespace App\Providers;

use App\Listeners\LogAuthFailed;
use App\Listeners\LogAuthLogin;
use App\Listeners\LogAuthLogout;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Login::class => [LogAuthLogin::class],
        Logout::class => [LogAuthLogout::class],
        Failed::class => [LogAuthFailed::class],
    ];
}
