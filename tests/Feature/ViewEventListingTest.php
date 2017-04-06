<?php

namespace Tests\Feature;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ViewEventListingTest extends TestCase
{
    use DatabaseMigrations;
    
    /**
      * @test
      */
    function user_can_view_event()
    {
        $event = factory(Event::class)->create([
            'name' => 'Tour de France',
            'description' => 'Lorem ipsum lor amet..',
            'start_time' => Carbon::parse('1 July 2017')->setTime(12,00),
            'end_time' => Carbon::parse('22 July 2017')
        ]);
        
        $response = $this->get('/events/' . $event->id);
        
        $response->assertSee('Tour de France');
        $response->assertSee('1 July 2017 12:00PM - 22 July 2017 0:00AM');
        $response->assertSee('Lorem ipsum lor amet..');
    }
    
    /** @test */
    function event_happening_this_week_will_be_shown_in_sidebar()
    {
        $eventHappeningThisWeek = factory(Event::class)->create(['start_time' => Carbon::now()]);
        $secondEventHappeningThisWeek = factory(Event::class)->create(['start_time' => Carbon::now()]);
        $eventNotHappeningThisWeek = factory(Event::class)->create(['start_time' => Carbon::now()->addWeek()]);
        
        $this->get('/events/' . $eventHappeningThisWeek->id)
            ->assertSee($eventHappeningThisWeek->name)
            ->assertSee($secondEventHappeningThisWeek->name)
            ->assertDontSee($eventNotHappeningThisWeek->name);
    }
}
