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

    protected $sortOrder = 'asc';

    public function __construct() {
        $this->statusable = StatusType::statsTypes();
    }

    /**
     * Added sort order to $this->sortOrder string, use with builder
     *
     * @param  string $sortBy The sort string
     * @return $this
     */
    public function orderBy($sortBy)
    {
        $this->sortOrder = $this->filterSortBy($sortBy);

        return $this;
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
        return $this->transforms(Tracks::orderBy('id', $this->sortOrder)->get());
    }

    /**
     * Get paginate list of the queue track
     *
     * @param  $perPage     The number of per page
     * @return collection
     */
    public function paginate($perPage)
    {
        return $this->transformPaginator(Tracks::orderBy('id', $this->sortOrder)->paginate($perPage));
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

        $builder = Tracks::whereNotNull('queue_at');

        $methodName = 'filterBy'.ucfirst($status);

        if (method_exists($this, $methodName)) {
            $this->{$methodName}($builder);
        }

        $builder->orderBy('id', $this->sortOrder);

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

        $builder->orderBy('id', $this->sortOrder);

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
            if (!is_null($track->{$status})) {
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
            if (!is_null($track->{$status})) {
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
        $track->statusTime = Carbon::parse(Qwatcher::getTrackStatusTime($track))->format('H:i:s - d/m/Y');

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
        return $builder
            ->whereNull('process_at')
            ->whereNull('succeed_at')
            ->whereNull('failed_at');
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
        return $builder
            ->whereNotNull('process_at')
            ->whereNull('succeed_at')
            ->whereNull('failed_at');
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
            ->whereNotNull('succeed_at')
            ->whereNull('failed_at');
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
        return $builder
            ->whereNotNull('failed_at');
            ->whereNull('succeed_at')
    }

    /**
     * Filter sort order string, allowed only asc, desc
     *
     * @param  string $sortOrder The sort order string
     * @return stirng
     */
    protected function filterSortBy($sortOrder)
    {
        return in_array($sortOrder, ['asc', 'desc']) ? $sortOrder : 'asc';
    }
}
