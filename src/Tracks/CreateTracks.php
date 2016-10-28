<?php namespace Maqe\Qwatcher\Tracks;

use Maqe\Qwatcher\Tracks\Enums\StatusType;

class CreateTracks extends TracksAbstract
{
    public function __construct($id, $job = NULL)
    {
        return $this->pushToTracks($id, $job);
    }

    public function pushToTracks($id, $job = NULL)
    {
        return $this->create($id, $job);
    }
}
