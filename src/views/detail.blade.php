<h1><strong>Status:</strong> {{ $track->status }} at: {{ $track->statusTime }}</h1>

<p>
    <a href="/track/{{ $track->id }}">#{{ $track->id }}</a>
</p>
<p>
    <strong>Driver:</strong> {{ $track->driver }}
</p>
<p>
    <strong>Queue id:</strong> {{ $track->queue_id }}
</p>
<p>
    <strong>Job name:</strong> {{ $track->meta->job_name }}
</p>
<p>
    <strong>Attempts:</strong> {{ $track->attempts }}
</p>
<h3>Meta data: </h3>
<p>
    @foreach ($track->meta as $key => $value)
        <p><strong>{{ $key }}:</strong> {{ $value }}</p>
    @endforeach
</p>
<h3>Payload data: </h3>
<p>
{{ $track->payload }}
</p>
