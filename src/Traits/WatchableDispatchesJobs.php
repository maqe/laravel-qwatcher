<?php namespace Maqe\Qwatcher\Traits;

use DB;
use Illuminate\Contracts\Bus\Dispatcher;
use Maqe\Qwatcher\Facades\Qwatch;
use Maqe\Qwatcher\Tracks\QueueTracks;

trait WatchableDispatchesJobs
{
    /**
     * Dispatch a job to its appropriate handler.
     *
     * @param  mixed  $job
     * @param  array  $meta
     * @return mixed
     */
    public function dispatch($job, array $meta = [])
    {
        $id = app(Dispatcher::class)->dispatch($job);

        Qwatch::tracks(new QueueTracks($id, $job, $meta));

        return $id;
    }

    /**
     * Dispatch a command to its appropriate handler in the current process.
     *
     * @param  mixed  $job
     * @return mixed
     */
    public function dispatchNow($job)
    {
        Qwatch::queued($job);

        return app(Dispatcher::class)->dispatchNow($job);
    }
}
