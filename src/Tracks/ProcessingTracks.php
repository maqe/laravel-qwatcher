<?php namespace Maqe\Qwatcher\Tracks;

use Maqe\Qwatcher\Tracks\Enums\StatusType;

class ProcessingTracks extends TracksDatabase
{
    public function __construct($id)
    {
        parent::__construct($id, StatusType::PROCESS);
    }
}
