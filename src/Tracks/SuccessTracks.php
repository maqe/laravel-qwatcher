<?php namespace Maqe\Qwatcher\Tracks;

use Maqe\Qwatcher\Tracks\Enums\StatusType;

class SuccessTracks extends TracksAbstract
{
    public function __construct($job)
    {
        return $this->pushToTracks($job);
    }

    public function pushToTracks($job)
    {
        return $this->update($job, StatusType::SUCCESS);
    }
}
