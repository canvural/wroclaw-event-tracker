@component('layouts.master')
    <section id="events" class="mw8 center shadow-2">
        <div style="height: 400px;">
            <img src="{{$event->getFirstMediaUrl('cover')}}" class="w-100 h-100" alt="{{$event->name}}">
        </div>
        <div class="ph3">
            <div class="br bb bl">
                <h2 class="f2 lh-copy ma0">
                    {{$event->name}}
                </h2> by <a href="{{$event->place->path()}}">{{$event->place->name}}</a>
                <h2 class="subtitle">
                    {{$event->formatted_start_time}} - {{$event->formatted_end_time}}
                </h2>
            </div>
            <div class="column is-2">
                <a href="https://www.facebook.com/events/{{ $event->facebook_id }}" target="_blank" rel="nofollow">
                    See on Facebook
                </a>
                <div>
                    <form method="POST" action="/events/{{ $event->id }}/attend">
                        {{ csrf_field() }}

                        @if ($isLoggedIn)
                            <button type="submit" class="button is-info" {{$user->isAttending($event) ? 'disabled' : ''}}>
                                Attend to event
                            </button>
                        @endif

                        @if ($event->isInFuture())
                            <p>{{ $event->attendeeCount }} people going to this event!</p>
                        @else
                            <p>{{ $event->attendeeCount }} people went to this event!</p>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        <div class="box">{!! nl2br(e($event->description)) !!}</div>
    </section>
@endcomponent