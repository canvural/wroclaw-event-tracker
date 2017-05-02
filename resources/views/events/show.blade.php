@component('layouts.master')
    <section class="section" id="events">
        <div class="container">
            <div class="columns is-mobile">
                <div class="column is-9">
                    <figure class="image">
                        <img src="{{$event->getFirstMediaUrl()}}" alt="{{$event->name}}">
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