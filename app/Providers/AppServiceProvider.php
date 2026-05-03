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
        \Illuminate\Pagination\Paginator::useTailwind();

        // Global Strong Password Policy
        \Illuminate\Validation\Rules\Password::defaults(function () {
            $rule = \Illuminate\Validation\Rules\Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols();

            return app()->isProduction()
                ? $rule->uncompromised()
                : $rule;
        });

        // Dev Logging for Emails
        if (app()->environment('local')) {
            \Illuminate\Support\Facades\Event::listen(\Illuminate\Mail\Events\MessageSending::class, function ($event) {
                $to = collect($event->message->getTo())->map->getAddress()->implode(', ');
                \Illuminate\Support\Facades\Log::info("--- [MAIL] SENDING ---", [
                    'to' => $to,
                    'subject' => $event->message->getSubject(),
                ]);
            });

            \Illuminate\Support\Facades\Event::listen(\Illuminate\Mail\Events\MessageSent::class, function ($event) {
                $to = collect($event->message->getTo())->map->getAddress()->implode(', ');
                \Illuminate\Support\Facades\Log::info("--- [MAIL] SENT SUCCESS ---", [
                    'to' => $to,
                    'subject' => $event->message->getSubject(),
                ]);
            });
        }
    }
}
