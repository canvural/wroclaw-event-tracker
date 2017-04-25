@component('layouts.master')
    <section class="section" id="events">
        <div class="container">
            <div class="columns is-mobile">
                <div class="column is-12">
                    @foreach($places as $place)
                        <div class="box">
                            <a href="{{ $place->path() }}">{{$place->name}}</a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endcomponent