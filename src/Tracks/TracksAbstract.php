<?php namespace Maqe\Qwatcher\Tracks;

use Illuminate\Queue\Queue;
use Carbon\Carbon;
use Maqe\Qwatcher\Tracks\Enums\StatusType;

abstract class TracksAbstract extends Queue
{
    /**
     * Push job to tracks table
     *
     * @param  $id          The queue id
     * @param  $job         The queue job object
     * @param  array  $meta The meta data array
     * @return mixed
     */
    abstract public function pushToTracks($id, $job = null, array $meta = []);

    /**
     * Create tracks record from job
     *
     * @param  mixed    $job        The queue job object
     * @param  array    $meta       The meta data array
     * @return integer  $id         The new tracks id
     */
    protected function create($id, $job = null, array $meta = [])
    {
        return Tracks::create($this->prepareRecord($id, $job, $meta))->id;
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
    protected function prepareRecord($id, $job, array $meta = [])
    {
        return [
            'driver' => config('queue.default'),
            'queue_id' => $id,
            'payload' => $this->createPayload($job),
            'attempts' => 0,
            'job_name' => get_class($job),
            'meta' => json_encode($this->setMetaData($job, $meta)),
            'queue_at' => Carbon::now(),
        ];
    }

    /**
     * Include default meta data job_name into meta column
     *
     * @param $job                  The queue job object
     * @param array  $meta          The meta array after add default
     */
    protected function setMetaData($job, array $meta = [])
    {
        return array_add($meta, 'job_name', get_class($job));
    }
}
