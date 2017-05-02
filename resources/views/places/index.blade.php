@component('layouts.master')
    <section class="section" id="places">
        @foreach($places as $place)
            <div class="box">
                <a href="{{ $place->path() }}">{{$place->name}}</a>
            </div>
        @endforeach
    </section>
@endcomponent