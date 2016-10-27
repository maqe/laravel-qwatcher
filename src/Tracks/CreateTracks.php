<?php namespace Maqe\Qwatcher\Tracks;

class CreateTracks extends TracksAbstract
{
    public function __construct($job)
    {
        return $this->pushToTracks($job);
    }

    public function pushToTracks($job)
    {
        return $this->create($job);
    }
}
