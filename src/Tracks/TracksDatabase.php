<?php namespace Maqe\Qwatcher\Tracks;

use Carbon\Carbon;
use DB;
use Maqe\Qwatcher\Tracks;
use Maqe\Qwatcher\Tracks\Enums\StatusType;

abstract class TracksDatabase
{
    protected $id;

    protected $status;

    public function __construct($id, $status = StatusType::CREATE)
    {
        $this->id = $id;

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
                return $this->update($this->id, StatusType::PROCESS);
                break;
            case StatusType::SUCCEED:
                return $this->update($this->id, StatusType::SUCCEED);
                break;
            case StatusType::FAILED:
                return $this->update($this->id, StatusType::FAILED);
                break;

            default:

                break;
        }
    }

    protected function prepareRecord($job)
    {
        return [
            'driver' => 'database',
            'queue_id' => $job->getJobId(),
            'queue' => $job->getQueue(),
            'payload' => $job->getRaw(),
            'attempts' => $job->attempts(),
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

    public function create($job)
    {
        return Tracks::create($this->prepareRecord($job))->id;
    }

    public function update($queueId, $status)
    {
        return Tracks::where('queue_id', $queueId)->update(["{$status}_at" => Carbon::now()]);
    }
}
