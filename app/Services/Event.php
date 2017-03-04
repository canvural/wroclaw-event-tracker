<?php

namespace App\Services;

use App\Models\Event as EventModel;
use Carbon\Carbon;

class Event
{
    /**
     * @param $searchQuery
     * @return mixed
     */
    public function all($searchQuery)
    {
        return EventModel::whereBetween('start_time', [
                    Carbon::today(),
                    Carbon::today()->endOfWeek()
                ])
                ->search($searchQuery)
                ->with('place')->get()->groupBy('place_id');
    }
    
    public function weekly()
    {
        return EventModel::whereBetween('start_time', [
            Carbon::today(),
            Carbon::today()->endOfWeek()
        ])->inRandomOrder()->limit('5')->get();
    }
}