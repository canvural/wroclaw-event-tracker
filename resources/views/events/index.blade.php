@component('layouts.master')
    <section class="section">
        @foreach($events as $event)
            <div class="box">
                <a href="{{ $event->path() }}">{{$event->name}}</a>
            </div>
        @endforeach
    </section>
@endcomponent