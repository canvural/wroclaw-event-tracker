<?php

namespace Tests\Feature;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ViewEventsTest extends TestCase
{
    use DatabaseMigrations;
    
    /**
      * @test
      */
    function a_user_can_view_single_event()
    {
        $event = factory(Event::class)->create();
        
        $this->get($event->path())
            ->assertSee($event->name);
    }
    
    /** @test */
    function a_user_can_see_all_events()
    {
        $event = factory(Event::class)->create();
        
        $this->get('/events')
            ->assertSee($event->name);
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
