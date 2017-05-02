<?php

namespace Tests\Feature;

use App\Models\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AttendingEventsTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    function guests_cannot_attend_to_any_events()
    {
        $this->withExceptionHandling()
            ->post('/events/1/attend')
            ->assertRedirect('/login');
    }
    
    /** @test */
    public function an_authenticated_user_can_attend_to_any_event()
    {
        $this->signIn();
        $event = create(Event::class);
        
        $this->post('events/' . $event->id . '/attend');
        
        $this->assertCount(1, $event->attendees);
    }
    
    /** @test */
    public function an_authenticated_user_may_only_attend_an_event_once()
    {
        $this->signIn();
        $event = create(Event::class);
        
        try {
            $this->post('events/' . $event->id . '/attend');
            $this->post('events/' . $event->id . '/attend');
        } catch (\Exception $e) {
            $this->fail('Did not expect to insert the same record set twice.');
        }
        
        $this->assertCount(1, $event->attendees);
    }
}
