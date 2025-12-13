<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share username with all views
        View::composer('dasher', function ($view) {
            $user = auth()->guard('dasher')->user();
            if ($user) {
                $view->with('current_login', $user->first_name . '-' . $user->last_name);
            }
        });
    }
}
