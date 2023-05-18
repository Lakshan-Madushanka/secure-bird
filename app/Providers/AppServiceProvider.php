<?php

declare(strict_types=1);

namespace App\Providers;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        Model::shouldBeStrict((bool) $this->app->environment(['local', 'testing']));

        $this->bootLocally();

        $this->setupPasswordRules();


    }

    public function bootLocally(): void
    {
        if ( ! $this->app->isProduction()) {
            $this->reportTimeConsumingQueries();
        }
    }

    private function reportTimeConsumingQueries(): void
    {
        if ($this->app->runningInConsole()) {
            return;
        }

        $maxTimeLimit = 500; // in milliseconds

        DB::listen(static function (QueryExecuted $event) use ($maxTimeLimit): void {
            if ($event->time > 500) {
                throw new QueryException(
                    $event->connectionName,
                    $event->sql,
                    $event->bindings,
                    new Exception(message: "Individual database query exceeded {$maxTimeLimit}ms.")
                );
            }
        });
    }

    public function setupPasswordRules(): void
    {
        Password::defaults(function () {
            $rule = Password::min(8);

            return $this->app->isProduction()
                ? $rule
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
                : $rule;
        });
    }
}
