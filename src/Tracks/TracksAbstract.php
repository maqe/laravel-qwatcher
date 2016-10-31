<?php namespace Maqe\Qwatcher\Tracks;

use Illuminate\Queue\Queue;
use Carbon\Carbon;
use Maqe\Qwatcher\Tracks\Enums\StatusType;

abstract class TracksAbstract extends Queue
{
    /**
     * Push job to tracks table
     *
     * @return mixed
     */
    abstract public function pushToTracks($id, $job = null);

    /**
     * Create tracks record from job
     *
     * @param  mixed    $job        The queue job object
     * @return integer  $id         The new tracks id
     */
    protected function create($id, $job = null)
    {
        return Tracks::create($this->prepareRecord($id, $job))->id;
    }

    /**
     * Update status tracks record by queue id and status type
     *
     * @param  integer  $id         The queue id to track queue record
     * @param  enum     $status     The status tracks type
     * @return boolean|mixed        The update result
     */
    protected function update($id, $job, $status)
    {
        return Tracks::where('queue_id', $id)->update(['attempts' => $job->attempts(), "{$status}_at" => Carbon::now()]);
    }

    /**
     * Prepare record before create new record
     *
     * @param  mixed $job           The queue job object
     * @return array                The array for input to database
     */
    protected function prepareRecord($id, $job)
    {
        return [
            'driver' => config('qwatcher.driver'),
            'queue_id' => $id,
            'payload' => $this->createPayload($job),
            'attempts' => 0,
            'queue_at' => Carbon::now()
        ];
    }
}
