<?php

namespace Tests\Feature;

use App\Models\Event;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ViewEventListingTest extends TestCase
{
    use DatabaseMigrations;
    
    /**
      * @test
      */
    function user_can_view_event()
    {
        $event = Event::create([
            'name' => 'Tour de France',
            'start_time' => Carbon::parse('1 July 2017')->setTime(12,00),
            'end_time' => Carbon::parse('22 July 2017')
        ]);
        
        $response = $this->get('/events/' . $event->id);
        
        $response->assertSee('Tour de France');
        $response->assertSee('1 July 2017 12:00PM - 22 July 2017 0:00AM');
    }
}
