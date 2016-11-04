<?php namespace Maqe\Qwatcher;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Maqe\Qwatcher\Tracks\TracksInterface;
use Maqe\Qwatcher\Tracks\Tracks;
use Carbon\Carbon;

class Qwatcher
{
    public function __construct() {}

    /**
     * Insert or update Track table depand on TracksInterface sub class
     *
     * @param  TracksInterface $tracks      Sub class that implements TracksInterface
     * @return mixed
     */
    public function tracks(TracksInterface $tracks)
    {
        return $tracks;
    }

    /**
     * Get the list of the queue track
     *
     * @return collection
     */
    public function all()
    {
        return $this->transforms(Tracks::all());
    }

    /**
     * Get the track record by id
     *
     * @param  $id          The track id
     * @return object
     */
    public function getById($id)
    {
        return $this->transform(Tracks::where('id', $id)->firstOrFail());
    }

    /**
     * Get the track record by current status
     *
     * @param  $status      The track status
     * @return collection
     */
    public function getByStatus($status)
    {
        $builder = Tracks::where("queue_at", '>', '0000-00-00 00:00:00');
        $methodName = 'filterBy'.ucfirst($status);

        if (method_exists($this, $methodName)) {
            $this->{$methodName}($builder);
        } else {
            throw new \Exception('"'.$status.'" doesn\'t not exist');
        }

        return $this->transforms($builder->get());
    }

    /**
     * Get the track record by job name
     *
     * @param  $name        The job name
     * @return collection
     */
    public function getByJobName($name)
    {
        $collecName = str_replace('\\', '%',$name);
        $condition = "`tracks`.`meta` LIKE '%\"job_name\":\"{$collecName}\"%'";

        return $this->transforms(Tracks::whereRaw($condition)->get());
    }

    /**
     * Transform records in tracks collection
     *
     * @param  Collection $tracks   The tracks collection
     * @return Collection           The tracks collection after transform
     */
    protected function transform($track)
    {
        $track->meta = ($track->meta) ? json_decode($track->meta) : null;
        $track->payload = ($track->payload) ? json_decode($track->payload) : null;
        $track->payload->data->command = ($track->payload->data->command) ? unserialize($track->payload->data->command) : null;

        return $track;
    }

    /**
     * Transform records in tracks collection
     *
     * @param  Collection $tracks   The tracks collection
     * @return Collection           The tracks collection after transform
     */
    protected function transforms($tracks)
    {
        return $tracks->each(function($track) {
            $track = $this->transform($track);
        });
    }

    /**
     * Filter by process date is not null
     * - succeed and failed must be null
     *
     * @param  Builder $builder The tracks builder
     * @return Builder          The query builder with filter applied.
     */
    protected function filterByProcess(Builder $builder)
    {
        return $builder->where("process_at", '>', '0000-00-00 00:00:00')
            ->where('succeed_at', '=', '0000-00-00 00:00:00')
            ->where('failed_at', '=', '0000-00-00 00:00:00');
    }

    /**
     * Filter by succeed date is not null
     * - process is not null
     * - failed is null
     *
     * @param  Builder $builder The tracks builder
     * @return Builder          The query builder with filter applied.
     */
    protected function filterBySucceed(Builder $builder)
    {
        return $builder
            ->where('succeed_at', '>', '0000-00-00 00:00:00')
            ->where('failed_at', '=', '0000-00-00 00:00:00');
    }

    /**
     * Filter by failed date is not null
     * - succeed is null
     *
     * @param  Builder $builder The tracks builder
     * @return Builder          The query builder with filter applied.
     */
    protected function filterByFailed(Builder $builder)
    {
        return $builder->where('succeed_at', '=', '0000-00-00 00:00:00')
            ->where('failed_at', '>', '0000-00-00 00:00:00');
    }
}
