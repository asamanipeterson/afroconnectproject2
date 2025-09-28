<?php

namespace App\Providers;

use App\Models\Stream;
use App\Policies\StreamPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Stream::class => StreamPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
