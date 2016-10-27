<?php namespace Maqe\Qwatcher;

use Maqe\Qwatcher\Tracks\CreateTracks;
use Maqe\Qwatcher\Tracks\FailedTracks;
use Maqe\Qwatcher\Tracks\ProcessingTracks;
use Maqe\Qwatcher\Tracks\SuccessTracks;

class Qwatcher
{
    public function __construct() {}

    public function queued($job)
    {
        (new CreateTracks($job));
    }

    public function succeed($connection, $job, $data)
    {
        (new SuccessTracks($job));
    }

    public function failed($connection, $job, $data)
    {
        (new FailedTracks($job));
    }
}
