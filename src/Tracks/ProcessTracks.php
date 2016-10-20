<?php namespace Maqe\Qwatcher\Tracks;

use Maqe\Qwatcher\Tracks\Enums\StatusType;

class ProcessTracks extends TracksAbstract
{
    public function __construct($id, $job = null)
    {
        return $this->pushToTracks($id, $job);
    }

    public function pushToTracks($id, $job = null)
    {
        return $this->update($id, $job, StatusType::PROCESS);
    }
}
