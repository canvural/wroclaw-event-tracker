<?php

namespace Tests\Unit;

use App\Models\Event;
use App\Models\Place;
use App\Scrapers\Facebook\EventScraper;
use App\Services\Facebook;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Collection;
use Tests\TestCase;

class FacebookEventScraperTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @var  EventScraper */
    private $eventScraper;
    
    public function setUp()
    {
        parent::setUp();
        
        $this->eventScraper = $this->app->make(EventScraper::class);
    }
    
    /** @test */
    function it_can_fetch_events_from_facebook()
    {
        $fb = $this->getMockBuilder(Facebook::class)->getMock();
        $fb->method('fetchAllEventsForPlace')
            ->with(1, ['limit' => 1, 'fields' => ''])
            ->willReturn(Collection::make([]));
        
        $this->app->instance(Facebook::class, $fb);
    
        $events = $this->eventScraper->fetch(1, ['limit' => 1, 'fields' => '']);

        $this->assertEquals(Collection::make([]), $events);
    }
    
    /** @test */
    function it_will_fetch_the_existing_model_rather_than_creating_new_while_transforming()
    {
        $place = factory(Place::class)->create();
        
        factory(Event::class)->create(['facebook_id' => 123]);
        factory(Event::class)->create(['facebook_id' => 321]);
    
        $eventCollection = $this->eventScraper
            ->transformToModel($place->id, collect([
                ['id' => 123, 'name' => 'event'],
                ['id' => 321, 'name' => 'Awesome event'],
            ]));
    
        $this->assertInstanceOf(Collection::class, $eventCollection);
        $this->assertEquals(2, $eventCollection->count());
        $eventCollection->map(function ($event) {
            $this->assertInstanceOf(Event::class, $event);
            $this->assertTrue($event->exists);
        });
    }
    
    /** @test */
    function it_can_transform_collection_of_raw_data_to_collection_of_event_models()
    {
        $place = factory(Place::class)->create();
        
        $eventData = [
            [
                'id' => '12345',
                'name' => 'This is a cool event',
                'description' => 'This is a really cool event. You should not miss it!!',
                'start_time' => '11/03/2017',
                'end_time' => '12/03/2017',
                'interested_count' => 10,
                'maybe_count' => 1,
            ],
            [
                'id' => '123456789',
                'name' => 'This is another cool event',
                'description' => 'This is a really cool event. You should not miss it too!!',
                'start_time' => '11/05/2018',
                'end_time' => '12/05/2018',
                'interested_count' => 10,
                'maybe_count' => 1,
            ]
        ];
        
        $eventCollection = $this->eventScraper
                            ->transformToModel($place->id, collect($eventData));
        
        $this->assertInstanceOf(Collection::class, $eventCollection);
        $this->assertEquals(2, $eventCollection->count());
        $eventCollection->map(function ($event) {
            $this->assertInstanceOf(Event::class, $event);
            
            $this->assertArrayHasKey('interested_count', $event->extra_info);
            $this->assertArrayHasKey('maybe_count', $event->extra_info);
        });
    }
    
    /** @test */
    function it_can_save_collection_of_events_to_database()
    {
        $events = make(Event::class, [], 5);
        
        $this->eventScraper->save($events);
        
        $events->each(function ($event) {
            $this->assertDatabaseHas('events', [
                'name' => $event->name
            ]);
        });
    }
}
