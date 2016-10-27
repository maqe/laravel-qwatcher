<?php namespace Maqe\Qwatcher\Tracks;

class ProcessingTracks extends TracksAbstract
{
    public function __construct($job)
    {
        return $this->pushToTracks($job);
    }

    public function pushToTracks($job)
    {
        return $this->update($job, StatusType::PROCESS);
    }
}
