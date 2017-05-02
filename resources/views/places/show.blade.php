@component('layouts.master')
    <section class="section" id="places">
        <div class="container">
            <div class="columns is-mobile">
                <div class="column">
                    <div class="box">{{$place->name}}</div>
                    <div class="box">{!! nl2br(e($place->description)) !!}</div>
                    <div class="box">
                        {{ $place->location['street'] }} <br>
                        {{ $place->location['zip'] }}, {{ $place->location['city'] }}
                    </div>
                </div>
            </div>

            <div class="columns is-mobile is-multiline">
                @foreach($place->events as $event)
                    <div class=" column is-2 card">
                        <div class="card-image">
                            <figure class="image is-48x48">
                                <img src="{{ $event->getFirstMediaUrl('cover', 'thumbnail') }}">
                            </figure>
                        </div>
                        <div class="card-content">
                            <div class="media">
                                <div class="media-content">
                                    <p class="title is-4">{{ $event->name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endcomponent