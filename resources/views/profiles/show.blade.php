@component('layouts.master')
    <section class="section">
        <div class="container">
            <div class="columns">
                <div class="column is-8">
                    <h3 class="title">{{ $profileUser->name }}</h3>

                    <h4 class="title is-4">Past Events</h4>
                    <ul>
                    @foreach($profileUser->events as $event)
                        <li><a href="{{ $event->path() }}">{{ $event->name }}</a></li>
                    @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endcomponent