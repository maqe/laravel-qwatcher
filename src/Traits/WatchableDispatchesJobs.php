<?php

namespace Maqe\Qwatcher\Traits;

use DB;
use Illuminate\Contracts\Bus\Dispatcher;

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
        $id = app(Dispatcher::class)->dispatch($job);

        \Maqe\Qwatcher\Facades\Qwatch::queued($id, $job);

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
        \Maqe\Qwatcher\Facades\Qwatch::queued($job);

        return app(Dispatcher::class)->dispatchNow($job);
    }
}
