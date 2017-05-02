<?php

namespace Tests\Unit;

use App\Models\Event;
use App\Models\Place;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EventTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    function it_has_a_place()
    {
        $event = factory(Event::class)->create();
        
        $this->assertInstanceOf(Place::class, $event->place);
    }
    
    /**
      * @test
      */
    function it_can_format_start_time()
    {
        $event = factory(Event::class)->states('bare')->make([
            'start_time' => Carbon::parse('1 April 1991')->setTime(12,30)
        ]);
        
        $this->assertEquals('1 April 1991 12:30PM', $event->formatted_start_time);
    }
    
    /**
     * @test
     */
    function it_can_format_end_time()
    {
        $event = factory(Event::class)->states('bare')->make([
            'end_time' => Carbon::parse('12 April 1991')->setTime(12,30)
        ]);
        
        $this->assertEquals('12 April 1991 12:30PM', $event->formatted_end_time);
    }
    
    /**
      * @test
      */
    function formatted_start_time_will_show_today()
    {
        $event = factory(Event::class)->states('bare')->make([
            'start_time' => Carbon::now()->setTime(12,0)
        ]);
    
        $this->assertEquals('Today at 12PM', $event->formatted_start_time);
    }
    
    /**
     * @test
     */
    function formatted_start_time_will_show_tomorrow()
    {
        $event = factory(Event::class)->states('bare')->make([
            'start_time' => Carbon::parse('+1 day')->setTime(12,0)
        ]);
        
        $this->assertEquals('Tomorrow at 12PM', $event->formatted_start_time);
    }
    
    /**
      * @test
      */
    function formatted_end_time_will_only_show_time_if_it_is_today_or_tomorrow()
    {
        $event = factory(Event::class)->states('bare')->make([
            'end_time' => Carbon::now()->setTime(12,0)
        ]);
    
        $eventTomorrow = factory(Event::class)->states('bare')->make([
            'end_time' => Carbon::parse('+1 day')->setTime(12,0)
        ]);
    
        $this->assertEquals('12PM', $event->formatted_end_time);
        $this->assertEquals('12PM', $eventTomorrow->formatted_end_time);
    }
    
    /** @test */
    function an_event_has_attendee_count()
    {
        $this->signIn();
        
        $event = create(Event::class);
        
        $event->attendees()->attach(auth()->id());
        
        $this->assertEquals(1, $event->attendeeCount);
    }
}
