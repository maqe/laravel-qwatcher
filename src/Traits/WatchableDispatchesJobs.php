<?php

namespace Maqe\Qwatcher\Traits;

use Illuminate\Contracts\Bus\Dispatcher;

trait WatchableDispatchesJobs
{
    /**
     * Dispatch a job to its appropriate handler.
     *
     * @param  mixed  $job
     * @return mixed
     */
    protected function dispatch($job)
    {
        \Maqe\Qwatcher\Facades\Qwatch::queued($job);

        return app(Dispatcher::class)->dispatch($job);
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
