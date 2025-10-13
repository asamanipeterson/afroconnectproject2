<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Conversation;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;


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


          Schema::defaultStringLength(191);

            Schema::defaultStringLength(191);


    }
}
