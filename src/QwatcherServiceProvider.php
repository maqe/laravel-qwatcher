<?php namespace Maqe\Qwatcher;

use Queue;
use Illuminate\Support\ServiceProvider;
use Maqe\Qwatcher\Tracks\SuccessTracks;
use Illuminate\Support\Facades\Log;

class QwatcherServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Queue::after(function ($connection, $job, $data) {
            \Maqe\Qwatcher\Facades\Qwatch::succeed($connection, $job, $data);

            if (env('QUEUE_DRIVER') == 'database') {
                (new SuccessTracks($job));
            }
        });

        Queue::failing(function ($connection, $job, $data) {
            \Maqe\Qwatcher\Facades\Qwatch::failed($connection, $job, $data);
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        /**
        * publish migrations
        */
        $this->publishes([__DIR__ . '/../database/migrations' => database_path('migrations')], 'migrations');
        $this->publishes([__DIR__ . '/../config/qwatcher.php' => config_path('qwatcher.php')], 'config');

        /**
        * merge config
        */
        $this->mergeConfigFrom(__DIR__ . '/../config/qwatcher.php', 'qwatcher');

        /**
        * Register Facade
        */
        $this->app->bind('Qwatch', function () {
            return (new Qwatcher);
        });

        /**
        * Register artisan Commands
        */
        // $this->commands([
        //     \Maqe\Qwatcher\Commands\SomeCommand1::class,
        //     \Maqe\Qwatcher\Commands\SomeCommand2::class
        // ]);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['Qwatch'];
    }
}
