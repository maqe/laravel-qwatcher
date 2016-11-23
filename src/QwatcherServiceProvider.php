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

        /**
         * Assgin namespace's view
         */
        $this->loadViewsFrom(__DIR__.'/views', 'tracks');
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

        /**
        * Register Facade
        */
        $this->app->bind('Qwatch', function () {
            return (new Qwatcher);
        });

        /**
         * Inblude Route
         */
        include __DIR__.'/routes.php';

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
