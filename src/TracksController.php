<?php namespace Maqe\Qwatcher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maqe\Qwatcher\Tracks\Tracks;
use Qwatcher;

class TracksController extends Controller
{
    public function index(Request $request)
    {
        $tracks = (is_null($request->input('per_page'))) ? Qwatcher::all() : Qwatcher::paginate($request->input('per_page'));

        return view('tracks::list', ['tracks' => $tracks]);
    }

    public function getByStatus(Request $request, $status)
    {
        $tracks = (is_null($request->input('per_page'))) ? Qwatcher::getByStatus($status) : Qwatcher::getByStatus($status, $request->input('per_page'));

        return view('tracks::list', ['tracks' => $tracks]);
    }

    public function getByJobName(Request $request, $jobName)
    {
        $tracks = (is_null($request->input('per_page'))) ? Qwatcher::getByJobName($jobName) : Qwatcher::getByJobName($jobName, $request->input('per_page'));

        return view('tracks::list', ['tracks' => $tracks]);
    }

    public function show(Request $request, $id)
    {
        $track = Qwatcher::getById($id);
        return view('tracks::detail', ['track' => $track]);
    }
}
