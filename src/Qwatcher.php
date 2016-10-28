<?php namespace Maqe\Qwatcher;

use Maqe\Qwatcher\Tracks\CreateTracks;
use Maqe\Qwatcher\Tracks\FailedTracks;
use Maqe\Qwatcher\Tracks\ProcessingTracks;
use Maqe\Qwatcher\Tracks\SuccessTracks;

class Qwatcher
{
    public function __construct() {}

    public function queued($id, $job)
    {
        (new CreateTracks($id, $job));
    }

    public function process($id, $job)
    {
        (new CreateTracks($id, $job));
    }

    public function succeed($id, $job)
    {
        (new SuccessTracks($id, $job));
    }

    public function failed($id, $job)
    {
        (new FailedTracks($id, $job));
    }
}
