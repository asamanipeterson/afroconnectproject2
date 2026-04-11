<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Conversation;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL; // ✅ ADD THIS

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot()
    {
        View::composer('layouts.sidebar', function ($view) {
            if (auth()->check()) {
                $latestConversation = Conversation::whereHas('participants', function ($query) {
                    $query->where('user_id', auth()->id());
                })->latest()->first();

                $view->with('latestConversation', $latestConversation);
            }
        });

        Schema::defaultStringLength(191); // keep only one

        if ($this->app->environment('production')) {
            URL::forceScheme('https'); // now works
        }
    }
}
