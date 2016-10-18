<?php namespace Maqe\Qwatcher;

class Qwatcher
{
    public function __construct() {}

    public function queued($job)
    {
        // dd($job);
    }

    public function succeed($connection, $job, $data)
    {
        // dd($connection, $job, $data);
    }

    public function failed($connection, $job, $data)
    {
        // dd($connection, $job, $data);
    }
}
