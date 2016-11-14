<?php namespace Maqe\Qwatcher;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Maqe\Qwatcher\Tracks\Enums\StatusType;
use Maqe\Qwatcher\Tracks\TracksInterface;
use Maqe\Qwatcher\Tracks\Tracks;
use Carbon\Carbon;

class Qwatcher
{
    protected $statusable = [];

    public function __construct() {
        $this->statusable = StatusType::statsTypes();
    }

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
     * Get paginate list of the queue track
     *
     * @param  $perPage     The number of per page
     * @return collection
     */
    public function paginate($perPage)
    {
        return $this->transformPaginator(Tracks::paginate($perPage));
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
     * @param  $perPage     The number of per page
     * @return collection
     */
    public function getByStatus($status, $per_page = null)
    {
        if(!in_array($status, $this->statusable)) {
            throw new \InvalidArgumentException('"'.$status.'" is not allowed in status type');
        }

        $builder = Tracks::where("queue_at", '>', '0000-00-00 00:00:00');

        $methodName = 'filterBy'.ucfirst($status);

        if (method_exists($this, $methodName)) {
            $this->{$methodName}($builder);
        }

        if (!is_null($per_page)) {
            return $this->transformPaginator($builder->paginate($per_page));
        } else {
            return $this->transforms($builder->get());
        }
    }

    /**
     * Get the track record by job name
     *
     * @param  $name        The job name
     * @param  $perPage     The number of per page
     * @return collection
     */
    public function getByJobName($name, $per_page = null)
    {
        $collecName = str_replace('\\', '%',$name);
        $condition = "`tracks`.`meta` LIKE '%\"job_name\":\"{$collecName}\"%'";

        if (!is_null($per_page)) {
            return $this->transformPaginator(Tracks::whereRaw($condition)->paginate($per_page));
        } else {
            return $this->transforms(Tracks::whereRaw($condition)->get());
        }
    }

    /**
     * Get current Status as text, tracking by sequential of status datetime
     *
     * @param  $track        The track object
     * @return string
     */
    public function getTrackStatus($track)
    {
        $sequentialStatus = ['failed_at', 'succeed_at', 'process_at', 'queue_at'];

        foreach ($sequentialStatus as $status) {
            if ($track->{$status} > '0000-00-00 00:00:00') {
                return substr($status, 0, -3);
            }
        }

        throw new Exception("Can't find the right column to track the Qwatcher", 1);

    }

    /**
     * Get current Status prias text, tracking by sequential of status datetime
     *
     * @param  $track        The track object
     * @return string
     */
    public function getTrackStatusTime($track)
    {
        $sequentialStatus = ['failed_at', 'succeed_at', 'process_at', 'queue_at'];

        foreach ($sequentialStatus as $status) {
            if ($track->{$status} > '0000-00-00 00:00:00') {
                return $track->{$status};
            }
        }

        throw new Exception("Can't find the right column to track the Qwatcher", 1);

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
        $track->status = ucfirst(Qwatcher::getTrackStatus($track));
        $track->statusTime = Carbon::parse(Qwatcher::getTrackStatusTime($track))->format('H:i - d/m/Y');

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
     * Transform records in tracks collection as paginate
     *
     * @param  Collection $tracks       The tracks collection
     * @return LengthAwarePaginator     The tracks LengthAwarePaginator after transform
     */
    protected function transformPaginator($tracks)
    {
        $queryString = [];
        parse_str($_SERVER['QUERY_STRING'], $queryString);

        return new LengthAwarePaginator($this->transforms($tracks), $tracks->total(), $tracks->perPage(), $tracks->currentPage(), ['path' => \URL::current(), 'query' => $queryString]);
    }

    /**
     * Filter by queue date is not null (get queue that not run the job yet)
     * - process, succeed and failed must be null
     *
     * @param  Builder $builder The tracks builder
     * @return Builder          The query builder with filter applied.
     */
    protected function filterByQueue(Builder $builder)
    {
        return $builder->where("process_at", '=', '0000-00-00 00:00:00')
            ->where('succeed_at', '=', '0000-00-00 00:00:00')
            ->where('failed_at', '=', '0000-00-00 00:00:00');
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
