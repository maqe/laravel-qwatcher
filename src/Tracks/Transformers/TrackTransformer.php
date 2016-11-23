<?php namespace Maqe\Qwatcher\Tracks\Transforms;

use Maqe\Qwatcher\Tracks\Tracks;

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
    public function transforms(Tracks $tracks)
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
    public function transformPaginator(Tracks $tracks)
    {
        $queryString = [];
        parse_str($_SERVER['QUERY_STRING'], $queryString);

        return new LengthAwarePaginator($this->transforms($tracks), $tracks->total(), $tracks->perPage(), $tracks->currentPage(), ['path' => \URL::current(), 'query' => $queryString]);
    }
}
