<?php namespace Maqe\Qwatcher\Tracks;

use Maqe\Qwatcher\Tracks\Enums\StatusType;

class ProcessingTracks extends TracksAbstract
{
    public function __construct($job)
    {
        $this->pushToTracks($job);
    }

    public function pushToTracks($job)
    {
        $this->update($job, StatusType::PROCESS);
    }
}
