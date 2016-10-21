<?php namespace Maqe\Qwatcher\Tracks;

use Maqe\Qwatcher\Tracks\Enums\StatusType;

class SuccessTracks extends TracksDatabase
{
    public function __construct($id)
    {
        parent::__construct($id, StatusType::SUCCEED);
    }
}
