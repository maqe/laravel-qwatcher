<?php namespace Maqe\Qwatcher\Tracks\Transformers;

use Illuminate\Database\Eloquent\Collection;
use Maqe\Qwatcher\Tracks\Tracks;
use Maqe\Qwatcher\Tracks\Transformers\TrackTransformerInterface;
use Carbon\Carbon;

class TrackTransformer implements TrackTransformerInterface
{
    /**
     * Transform records in tracks collection
     *
     * @param  Tracks $tracks   The tracks collection
     * @return Collection           The tracks collection after transform
     */
    public function transform(Tracks $track)
    {
        $track->meta = ($track->meta) ? json_decode($track->meta) : null;
        $track->status = ucfirst($this->getTrackStatus($track));
        $track->statusTime = Carbon::parse($this->getTrackStatusTime($track))->format('H:i:s - d/m/Y');

        return $track;
    }

    /**
     * Transform records in tracks collection
     *
     * @param  Collection $tracks   The tracks collection
     * @return Collection           The tracks collection after transform
     */
    public function transforms(Collection $tracks)
    {
        return $tracks->each(function ($track) {
            $track = $this->transform($track);
        });
    }

    /**
     * Transform records in tracks collection as paginate
     *
     * @param  Collection $tracks       The tracks collection
     * @return LengthAwarePaginator     The tracks LengthAwarePaginator after transform
     */
    public function transformPaginator(Collection $tracks)
    {
        $queryString = [];
        parse_str($_SERVER['QUERY_STRING'], $queryString);

        return new LengthAwarePaginator($this->transforms($tracks), $tracks->total(), $tracks->perPage(), $tracks->currentPage(), ['path' => \URL::current(), 'query' => $queryString]);
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
}
