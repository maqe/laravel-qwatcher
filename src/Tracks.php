<?php namespace Maqe\Qwatcher;

use Illuminate\Database\Eloquent\Model;

class Tracks extends Model
{
    protected $table = 'tracks';

    protected $fillable = [
        'driver', 'queue_id', 'queue', 'payload', 'attempts',
    ];

    protected $dates = [
        'created_at', 'processing_at', 'success_at', 'failed_at'
    ];

    public function setUpdatedAtAttribute($value)
    {
        // to Disable updated_at
    }

    public function getUpdatedAtColumn()
    {
        return null; // to Disable updated_at
    }
}
