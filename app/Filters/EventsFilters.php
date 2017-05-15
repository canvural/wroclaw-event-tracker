<?php
namespace App\Filters;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class EventsFilters extends Filters
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['date', 'popular'];
    
    /**
     * Filter the query by a given date.
     *
     * @param  string $date
     * @return Builder
     */
    protected function date($date)
    {
        $validDates = [
            'today' => [Carbon::today(), Carbon::tomorrow()],
            'week' => [Carbon::today()->startOfWeek(), Carbon::today()->endOfWeek()],
            'month' => [Carbon::today()->startOfMonth(), Carbon::today()->endOfMonth()]
        ];
        
        $filter = $validDates['today'];
        
        if (array_key_exists($date, $validDates)) {
            $filter = $validDates[$date];
        }
        
        return $this->builder->whereBetween('start_time', $filter);
    }
    
    /**
     * Filter the query according to most popular threads.
     *
     * @return Builder
     */
    protected function popular()
    {
        $this->builder->getQuery()->orders = [];
        
        return $this->builder->orderBy('replies_count', 'desc');
    }
}