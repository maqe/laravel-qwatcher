<?php namespace Maqe\Qwatcher;

use Queue;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use Maqe\Qwatcher\Facades\Qwatch;
use Maqe\Qwatcher\Tracks\FailedTracks;
use Maqe\Qwatcher\Tracks\ProcessTracks;
use Maqe\Qwatcher\Tracks\SucceedTracks;

class QwatcherServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Queue::before(function ($job) {
            Qwatch::tracks(new ProcessTracks($job->job->getJobId(), $job->job));
        });

        Queue::after(function ($job) {
            Qwatch::tracks(new SucceedTracks($job->job->getJobId(), $job->job));
        });

        Queue::failing(function ($job) {
            Qwatch::tracks(new FailedTracks($job->job->getJobId(), $job->job));
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
