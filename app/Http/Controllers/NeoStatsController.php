<?php

namespace App\Http\Controllers;

use App\Jobs\FetchNeoDataJob;
use App\Http\Requests\NeoStatsRequest;

class NeoStatsController extends Controller
{
    public function index()
    {
        return view('neo-stats');
    }

    public function fetchNeoStats(NeoStatsRequest $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Dispatch job to handle API fetching
        $job = new FetchNeoDataJob($startDate, $endDate);
        $job->handle();

        $response = $job->getData();

        if (!$response) {
            return back()->withErrors(['error' => 'Failed to fetch data from NASA API.']);
        }

        return view('neo-stats', $response);
    }

    public function clearFilters()
    {
        session()->forget(['start_date', 'end_date']);
        return redirect('/')->with(["message" => "Filters cleared"]);
    }
}
