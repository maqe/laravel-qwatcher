<?php namespace Maqe\Qwatcher\Tracks;

use Carbon\Carbon;
use DB;
use Maqe\Qwatcher\Tracks;
use Maqe\Qwatcher\Tracks\Enums\StatusType;

abstract class TracksAbstract
{
    abstract public function pushToTracks($job);

    protected function create($job)
    {
        return Tracks::create($this->prepareRecord($job))->id;
    }

    protected function update($id, $status)
    {
        return Tracks::where('queue_id', $id)->update(["{$status}_at" => Carbon::now()]);
    }

    protected function prepareRecord($job)
    {
        return [
            'driver' => config('qwatcher.driver'),
            'queue_id' => $job->getJobId(),
            'payload' => $job->getRawBody(),
            'attempts' => $job->attempts(),
            'created_at' => Carbon::now()
        ];
    }
}
