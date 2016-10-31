<?php namespace Maqe\Qwatcher\Tracks;

class QueueTracks extends TracksAbstract
{
    public function __construct($id, $job = null)
    {
        return $this->pushToTracks($id, $job);
    }

    public function pushToTracks($id, $job = null)
    {
        return $this->create($id, $job);
    }
}
