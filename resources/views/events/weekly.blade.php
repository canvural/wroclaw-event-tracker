@inject('eventService', 'App\Services\Event')
<div class="card">
    <header class="card-header">
        <p class="card-header-title">
            Events Happening This Week
        </p>
        <a class="card-header-icon">
      <span class="icon">
        <i class="fa fa-angle-down"></i>
      </span>
        </a>
    </header>
    <div class="card-content">
        <div class="content">
            @foreach($eventService->weekly() as $e)
                <article class="media">
                    <figure class="media-left">
                        <p class="image is-32x32">
                            @if(!$e->media->isEmpty())
                            <img src="{{$e->media->first()->getUrl('thumbnail')}}">
                            @else
                            <img src="" alt="">
                            @endif
                        </p>
                    </figure>
                    <div class="media-content">
                        <div class="content">
                            <p>
                                <strong><a href="{{ route('events.show', $e->id) }}">{{$e->name}}</a></strong>
                            </p>
                        </div>
                        <p class="subtitle">{{ $e->formatted_start_time }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
    {{--<footer class="card-footer">--}}
        {{--<a class="card-footer-item">Save</a>--}}
        {{--<a class="card-footer-item">Edit</a>--}}
        {{--<a class="card-footer-item">Delete</a>--}}
    {{--</footer>--}}
</div>