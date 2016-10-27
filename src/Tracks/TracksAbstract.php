<?php namespace Maqe\Qwatcher\Tracks;

use Carbon\Carbon;
use Maqe\Qwatcher\Tracks\Enums\StatusType;

abstract class TracksAbstract
{
    /**
     * Push job to tracks table
     *
     * @return mixed
     */
    abstract public function pushToTracks($job);

    /**
     * Create tracks record from job
     *
     * @param  mixed    $job        The queue job object
     * @return integer  $id         The new tracks id
     */
    protected function create($job)
    {
        return Tracks::create($this->prepareRecord($job))->id;
    }

    /**
     * Update status tracks record by queue id and status type
     *
     * @param  integer  $id         The queue id to track queue record
     * @param  enum     $status     The status tracks type
     * @return boolean|mixed        The update result
     */
    protected function update($id, $status)
    {
        return Tracks::where('queue_id', $id)->update(["{$status}_at" => Carbon::now()]);
    }

    /**
     * Prepare record before create new record
     *
     * @param  mixed $job           The queue job object
     * @return array                The array for input to database
     */
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
