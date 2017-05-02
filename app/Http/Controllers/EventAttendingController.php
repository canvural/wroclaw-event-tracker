<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventAttendingController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Stores new attending record for the current user and event.
     *
     * @param Event $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Event $event)
    {
        $user = auth()->user();
        
        if (!$user->isAttending($event)) {
            $event->attendees()->attach($user->id);
        }
        
        return back();
    }
}
