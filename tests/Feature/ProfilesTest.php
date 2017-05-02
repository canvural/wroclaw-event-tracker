<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProfilesTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    function a_user_has_a_profile()
    {
        $user = create(User::class);
        
        $this->get("/profiles/{$user->name}")
            ->assertSee($user->name);
    }
    
    /** @test */
    function profile_displays_the_events_that_user_attended_in_the_past()
    {
        $user = create(User::class);
        $eventDidAttend = create(Event::class);
        $eventThatDidNotAttend = create(Event::class);
        
        $eventDidAttend->attendees()->attach($user->id);
        
        $this->signIn($user)
            ->get("profiles/{$user->name}")
            ->assertSee($eventDidAttend->name)
            ->assertDontSee($eventThatDidNotAttend->name);
    }
}
