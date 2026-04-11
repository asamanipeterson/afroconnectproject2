<?php

namespace App\Providers;

use App\Models\Conversation;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Handle Sidebar Conversation Data
        View::composer('layouts.sidebar', function ($view) {
            if (auth()->check()) {
                $latestConversation = Conversation::whereHas('participants', function ($query) {
                    $query->where('user_id', auth()->id());
                })->latest()->first();

                $view->with('latestConversation', $latestConversation);
            }
        });

        // Database Schema Default
        Schema::defaultStringLength(191);

        // Force HTTPS in Production (Render)
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
