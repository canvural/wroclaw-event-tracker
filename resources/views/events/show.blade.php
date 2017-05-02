@component('layouts.master')
    <section class="section" id="events">
        <div class="container">
            <div class="columns is-mobile">
                <div class="column is-9">
                    <figure class="image is-2by1">
                        <img src="{{$event->getFirstMediaUrl('cover')}}" alt="{{$event->name}}">
                    </figure>
                    <div class="box columns">
                        <div class="column is-10">
                            <h3 class="title is-3">{{$event->name}}</h3> by <a href="{{$event->place->path()}}">{{$event->place->name}}</a>
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
                </div>
                <div class="column is-3" id="events-happening-this-week">
                    @include('events.weekly')
                </div>
            </div>
        </div>
    </section>
@endcomponent