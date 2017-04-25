@component('layouts.master')
    <section class="section" id="places">
        <div class="container">
            <div class="columns is-mobile">
                <div class="column">
                    <div class="box">{{$place->name}}</div>
                    <div class="box">{!! nl2br(e($place->description)) !!}</div>
                </div>
            </div>

            <div class="columns is-mobile is-multiline">
                @foreach($place->events as $event)
                    <div class="column">
                        <div class="box"><a href="{{ $event->path() }}">{{$event->name}}</a></div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endcomponent