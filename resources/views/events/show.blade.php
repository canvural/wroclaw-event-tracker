@component('layouts.master')
    <section class="section" id="events">
        <div class="container">
            <div class="columns is-mobile">
                <div class="column is-9">
                    <figure class="image">
                        <img src="{{$event->getFirstMediaUrl()}}" alt="{{$event->name}}">
                    </figure>
                    <nav class="level">
                        <div class="level-left">
                            <h3 class="title is-3">{{$event->name}}</h3>
                            <h2 class="subtitle">
                                {{$event->formatted_start_time}} - {{$event->formatted_end_time}}
                            </h2>
                        </div>
                        <div class="level-right">
                            <p class="level-item">
                                <a href="https://www.facebook.com/events/{{ $event->facebook_id }}" rel="nofollow">
                                    See on Facebook
                                </a>
                            </p>
                        </div>
                    </nav>
                </div>
                <div class="column is-3" id="events-happening-this-week">
                    @include('events.weekly')
                </div>
            </div>
        </div>
    </section>
@endcomponent