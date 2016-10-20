<?php namespace Maqe\Qwatcher\Tracks;

use Carbon\Carbon;
use DB;
use Maqe\Qwatcher\Tracks;
use Maqe\Qwatcher\Tracks\Enums\StatusType;

public class TracksDatabase
{
    protected $id;

    protected $job;

    protected $status;

    public function __construct($id, $job = null, $status = StatusType::CREATE)
    {
        $this->id = $id;

        $this->job = $job;

        $this->status = $status;

        $this->manage();
    }

    protected function manage()
    {
        $job = $this->getQueueFromJobsTableById($this->id);

        switch ($this->status) {
            case StatusType::CREATE:
                return $this->create($job);
                break;

            case StatusType::PROCESS:
                return $this->update($this->job->getJobId(), StatusType::PROCESS);
                break;
            case StatusType::SUCCEED:
                return $this->update($this->job->getJobId(), StatusType::SUCCEED);
                break;
            case StatusType::FAILED:
                return $this->update($this->job->getJobId(), StatusType::FAILED);
                break;

            default:

                break;
        }
    }

    protected function prepareRecord($job)
    {
        return [
            'driver' => 'database',
            'queue_id' => $job->id,
            'queue' => $job->queue,
            'payload' => $job->payload,
            'attempts' => $job->attempts,
            'created_at' => Carbon::now()
        ];
    }

    protected function getQueueFromJobsTableById($id)
    {
        if ($this->status != StatusType::CREATE) return;

        try {
            return DB::table('jobs')->where('id', $id)->first();
        } catch (\ModelNotFoundException $e) {
            throw new ModelNotFoundException("Jobs table not found", 1);
        }
    }

    protected function create($job)
    {
        return Tracks::create($this->prepareRecord($job))->id;
    }

    public function update($queueId, $status)
    {
        return Tracks::where('queue_id', $queueId)->update(["{$status}_at" => Carbon::now()]);
    }
}
