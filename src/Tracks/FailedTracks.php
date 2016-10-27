<?php namespace Maqe\Qwatcher\Tracks;

use Maqe\Qwatcher\Tracks\Enums\StatusType;

class FailedTracks extends TracksAbstract
{
    public function __construct($job)
    {
        $this->pushToTracks($job);
    }

    public function pushToTracks($job)
    {
        $this->update($job, StatusType::FAILED);
    }
}
