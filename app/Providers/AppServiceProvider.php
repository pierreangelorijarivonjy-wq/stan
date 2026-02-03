<?php

namespace App\Providers;

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
        if (app()->isProduction()) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Register event listeners
        \Illuminate\Support\Facades\Event::listen(
            \App\Events\UserLoggedIn::class,
            \App\Listeners\NotifyAdminOfUserLogin::class
        );

        \Illuminate\Support\Facades\Event::listen(
            \App\Events\UserRegistered::class,
            \App\Listeners\NotifyAdminOfUserRegistration::class
        );

        \Illuminate\Support\Facades\Event::listen(
            \App\Events\UserLoggedOut::class,
            \App\Listeners\NotifyAdminOfUserLogout::class
        );

        \Illuminate\Support\Facades\Event::listen(
            \App\Events\PaymentCompleted::class,
            [\App\Listeners\NotifyAdminOfPayment::class, 'handleCompleted']
        );

        \Illuminate\Support\Facades\Event::listen(
            \App\Events\PaymentFailed::class,
            [\App\Listeners\NotifyAdminOfPayment::class, 'handleFailed']
        );

        \Illuminate\Support\Facades\Event::listen(
            \App\Events\CourseAccessed::class,
            \App\Listeners\NotifyAdminOfCourseAccess::class
        );

        \Illuminate\Support\Facades\Event::listen(
            \App\Events\FileDownloaded::class,
            \App\Listeners\NotifyAdminOfFileDownload::class
        );

        \Illuminate\Support\Facades\Event::listen(
            \App\Events\ProfileUpdated::class,
            \App\Listeners\NotifyAdminOfProfileUpdate::class
        );
    }
}
