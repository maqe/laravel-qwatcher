<?php namespace Maqe\Qwatcher\Tracks;

use Maqe\Qwatcher\Tracks\Enums\StatusType;

class SuccessTracks extends TracksDatabase
{
    public function __construct($job)
    {
        parent::__construct(0, $job, StatusType::SUCCEED);
    }
}
