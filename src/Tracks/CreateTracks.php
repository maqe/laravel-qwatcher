<?php namespace Maqe\Qwatcher\Tracks;

use Maqe\Qwatcher\Tracks\Enums\StatusType;

class CreateTracks extends TracksDatabase
{
    public function __construct($id)
    {
        parent::__construct($id, null, StatusType::CREATE);
    }
}
