@component('layouts.master')
    <section class="section" id="places">
        <div class="columns is-mobile">
            <div class="column">
                <div class="box">{{$place->name}}</div>

                @if (!empty($place->description))
                <div class="box">{!! nl2br(e($place->description)) !!}</div>
                @endif
                <div class="box">
                    <b>Address:</b> <br>
                    {{ $place->location['street'] }} <br>
                    {{ $place->location['zip'] }}, {{ $place->location['city'] }}
                </div>
            </div>
        </div>

        <div class="columns is-mobile is-multiline">
            @foreach($place->events as $event)
                <div class=" column is-3 card">
                    @if(!$event->media->isEmpty())
                        <div class="card-image">
                            <figure class="image is-48x48">
                                <img src="{{ $event->media->first()->getUrl('thumbnail') }}">
                            </figure>
                        </div>
                    @endif
                    <div class="card-header">
                        <div class="card-header-title">
                            <a href="{{ $event->path() }}">{{ $event->name }}</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endcomponent