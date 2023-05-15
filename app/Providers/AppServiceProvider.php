<?php

declare(strict_types=1);

namespace App\Providers;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
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
        Model::shouldBeStrict((bool) $this->app->environment(['local', 'testing']));
    }

    public function registerLocally(): void
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
}
