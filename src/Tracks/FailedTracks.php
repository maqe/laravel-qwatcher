<?php namespace Maqe\Qwatcher\Tracks;

use Maqe\Qwatcher\Tracks\Enums\StatusType;

class FailedTracks extends TracksAbstract
{
    public function __construct($job)
    {
        return $this->pushToTracks($job);
    }

    public function pushToTracks($job)
    {
        return $this->update($job, StatusType::FAILED);
    }
}
