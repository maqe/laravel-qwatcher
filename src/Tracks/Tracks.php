<?php namespace Maqe\Qwatcher\Tracks;

use Illuminate\Database\Eloquent\Model;

class Tracks extends Model
{
    protected $table = 'tracks';

    protected $fillable = [
        'driver', 'queue_id', 'queue', 'payload', 'attempts', 'job_name',
        'meta', 'queue_at', 'process_at', 'success_at', 'failed_at'
    ];

    public $timestamps = false;
}
