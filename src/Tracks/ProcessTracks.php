<?php namespace Maqe\Qwatcher\Tracks;

use Maqe\Qwatcher\Tracks\Enums\StatusType;

class ProcessTracks extends TracksAbstract implements TracksInterface
{
    public function __construct($id, $job = null, array $meta = [])
    {
        return $this->pushToTracks($id, $job);
    }

    public function pushToTracks($id, $job = null, array $meta = [])
    {
        return $this->update($id, $job, StatusType::PROCESS);
    }
}
