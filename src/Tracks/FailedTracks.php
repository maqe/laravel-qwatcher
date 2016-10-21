<?php namespace Maqe\Qwatcher\Tracks;

use Maqe\Qwatcher\Tracks\Enums\StatusType;

class FailedTracks extends TracksDatabase
{
    public function __construct($job)
    {
        parent::__construct(0, $job, StatusType::FAILED);
    }
}
