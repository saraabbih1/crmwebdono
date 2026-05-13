<?php

namespace App\Providers;

use App\Models\Client;
use App\Models\Notification;
use App\Models\Subscription;
use App\Policies\ClientPolicy;
use App\Policies\NotificationPolicy;
use App\Policies\SubscriptionPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Pagination\Paginator;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
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
        Paginator::useBootstrapFive();

        Gate::policy(Client::class, ClientPolicy::class);
        Gate::policy(Subscription::class, SubscriptionPolicy::class);
        Gate::policy(Notification::class, NotificationPolicy::class);

        RateLimiter::for('login', fn (Request $request) => Limit::perMinute(5)->by($request->ip()));
        RateLimiter::for('register', fn (Request $request) => Limit::perMinute(3)->by($request->ip()));
        RateLimiter::for('password.email', fn (Request $request) => Limit::perMinute(3)->by($request->ip()));
        RateLimiter::for('api', fn (Request $request) => Limit::perMinute(60)->by($request->user()?->id ?: $request->ip()));

        if (Schema::hasTable('settings')) {
            $settings = app(\App\Services\SettingsService::class);

            config([
                'mail.default' => $settings->get('smtp_mailer', config('mail.default')),
                'mail.mailers.smtp.host' => $settings->get('smtp_host', config('mail.mailers.smtp.host')),
                'mail.mailers.smtp.port' => $settings->get('smtp_port', config('mail.mailers.smtp.port')),
                'mail.mailers.smtp.username' => $settings->get('smtp_username', config('mail.mailers.smtp.username')),
                'mail.from.address' => $settings->get('smtp_from_address', config('mail.from.address')),
                'mail.from.name' => $settings->get('smtp_from_name', config('mail.from.name')),
            ]);
        }
    }
}
