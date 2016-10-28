<?php namespace Maqe\Qwatcher\Tracks;

use Maqe\Qwatcher\Tracks\Enums\StatusType;

class FailedTracks extends TracksAbstract
{
    public function __construct($id, $job = NULL)
    {
        return $this->pushToTracks($id, $job = NULL);
    }

    public function pushToTracks($id, $job = NULL)
    {
        return $this->update($id, $job, StatusType::FAILED);
    }
}
