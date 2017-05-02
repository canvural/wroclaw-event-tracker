<?php

namespace Tests\Feature;


use App\Models\Event;
use App\Models\Place;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ViewPlacesTest extends TestCase
{
    use DatabaseMigrations;
    
    /**
     * @var Place
     */
    private $place;
    
    public function setUp()
    {
        parent::setUp();
        
        $this->place = factory(Place::class)->create();
    }
    
    /** @test */
    function a_user_can_see_all_places()
    {
        $this->get('/places')
            ->assertSee($this->place->name);
    }
    
    /**
     * @test
     */
    function a_user_can_view_single_place()
    {
        $this->get($this->place->path())
            ->assertSee($this->place->name);
    }
    
    /** @test */
    function a_user_can_read_events_that_are_associated_with_a_place()
    {
        $event = factory(Event::class)->create(['place_id' => $this->place->id]);
        
        $this->get($this->place->path())
            ->assertSee($event->name);
    }
}