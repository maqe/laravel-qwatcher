<?php

namespace Maqe\Qwatcher\Traits;

use DB;
use Illuminate\Contracts\Bus\Dispatcher;
use Maqe\Qwatcher\Tracks\CreateTracks;

trait WatchableDispatchesJobs
{
    /**
     * Dispatch a job to its appropriate handler.
     *
     * @param  mixed  $job
     * @return mixed
     */
    public function dispatch($job)
    {
        \Maqe\Qwatcher\Facades\Qwatch::queued($job);

        $id = app(Dispatcher::class)->dispatch($job);

        if (env('QUEUE_DRIVER') == 'database') {
            (new CreateTracks($id));
        }

        return $id;

        // return app(Dispatcher::class)->dispatch($job);
    }

    /**
     * Dispatch a command to its appropriate handler in the current process.
     *
     * @param  mixed  $job
     * @return mixed
     */
    public function dispatchNow($job)
    {
        \Maqe\Qwatcher\Facades\Qwatch::queued($job);

        return app(Dispatcher::class)->dispatchNow($job);
    }
}
