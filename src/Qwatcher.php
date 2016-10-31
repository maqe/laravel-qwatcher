<?php namespace Maqe\Qwatcher;

use Maqe\Qwatcher\Tracks\QueueTracks;
use Maqe\Qwatcher\Tracks\FailedTracks;
use Maqe\Qwatcher\Tracks\ProcessTracks;
use Maqe\Qwatcher\Tracks\SucceedTracks;

class Qwatcher
{
    public function __construct() {}

    public function queued($id, $job)
    {
        (new QueueTracks($id, $job));
    }

    public function process($id, $job)
    {
        (new ProcessTracks($id, $job));
    }

    public function succeed($id, $job)
    {
        (new SucceedTracks($id, $job));
    }

    public function failed($id, $job)
    {
        (new FailedTracks($id, $job));
    }
}
