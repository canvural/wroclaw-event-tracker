<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EventsController extends Controller
{
    /**
     * Get all events.
     *
     * @param Request $request
     * @return mixed JSON for using in front-end.
     */
    public function all(Request $request)
    {
        $searchQuery = $request->input('q');
        
        return Event::whereBetween('start_time', [
                    Carbon::now(),
                    Carbon::now()->addWeek()
                ])
                ->search($searchQuery)
                ->with('place')->get()->groupBy('place_id');
    }
}
