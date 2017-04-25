<?php

namespace Tests\Unit;

use App\Models\Event;
use App\Models\Place;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PlaceTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    function it_can_have_events()
    {
        $place = factory(Place::class)->create();
        
        $eventOne = factory(Event::class)->create(['place_id' => $place->id]);
        $eventTwo = factory(Event::class)->create(['place_id' => $place->id]);
        $eventThree = factory(Event::class)->create(['place_id' => $place->id]);
        
        $this->assertTrue($place->events->contains($eventOne));
        $this->assertTrue($place->events->contains($eventTwo));
        $this->assertTrue($place->events->contains($eventThree));
    }
}
