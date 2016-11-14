<style>
* {
    box-sizing: border-box;
}
.col-1 {width: 8.33%;}
.col-2 {width: 16.66%;}
.col-3 {width: 25%;}
.col-4 {width: 33.33%;}
.col-5 {width: 41.66%;}
.col-6 {width: 50%;}
.col-7 {width: 58.33%;}
.col-8 {width: 66.66%;}
.col-9 {width: 75%;}
.col-10 {width: 83.33%;}
.col-11 {width: 91.66%;}
.col-12 {width: 100%;}
[class*="col-"] {
    float: left;
    padding: 15px;
    word-wrap: break-word;
}
.row::after {
    content: "";
    clear: both;
    display: block;
}
</style>
@inject('statusType', 'Maqe\Qwatcher\Tracks\Enums\StatusType')

@if ($tracks->count() > 0)
    <div class="container">
    @foreach($tracks as $track)
        <div class="row">
            <div class="col-3">
                <div class="row">
                    <div class="col-12">
                        <strong>Status:</strong>
                        {{ ucfirst($statusType::getMessageByStatus($track->status)) }}
                        at: {{ $track->statusTime }}
                    </div>
                </div>
            </div>
            <div class="col-9">
                <div class="row">
                    <div class="col-1">
                        <a href="/tracks/{{ $track->id }}">#{{ $track->id }}</a>
                    </div>
                    <div class="col-2">
                        <strong>Driver:</strong> {{ $track->driver }}
                    </div>
                    <div class="col-7">
                        <strong>Job name:</strong> {{ $track->meta->job_name }}
                    </div>
                    <div class="col-2">
                        <strong>Attempts:</strong> {{ $track->attempts }}
                    </div>
                </div>

            </div>
        </div>
        <hr>
    @endforeach
    </div>

    @if (method_exists($tracks, 'render'))
        {!! $tracks->render() !!}
    @endif
@else
    <p>No result found</p>
@endif
