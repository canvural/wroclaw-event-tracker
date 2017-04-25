@component('layouts.master')
    <section class="section" id="events">
        <div class="container">
            <div class="columns is-mobile">
                <div class="column is-9">
                    @foreach($events as $event)
                        <div class="box">
                            <a href="{{ $event->path() }}">{{$event->name}}</a>
                        </div>
                    @endforeach
                </div>
                <div class="column is-3" id="events-happening-this-week">
                    @include('events.weekly')
                </div>
            </div>
        </div>
    </section>
@endcomponent