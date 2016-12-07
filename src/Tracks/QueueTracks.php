<?php namespace Maqe\Qwatcher\Tracks;

class QueueTracks extends TracksAbstract implements TracksInterface
{
    public function __construct($id, $job = null, array $meta = [])
    {
        return $this->pushToTracks($id, $job, $meta);
    }

    public function pushToTracks($id, $job = null, array $meta = [])
    {
        return $this->create($id, $job, $meta);
    }
}
