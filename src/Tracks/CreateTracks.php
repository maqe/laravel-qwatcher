<?php namespace Maqe\Qwatcher\Tracks;

use Maqe\Qwatcher\Tracks\Enums\StatusType;

class CreateTracks extends TracksAbstract
{
    public function __construct($job)
    {
        $this->pushToTracks($job);
    }

    public function pushToTracks($job)
    {
        $this->create($job);
    }
}
