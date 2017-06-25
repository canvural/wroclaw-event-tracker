<nav class="flex justify-between bb b--white-10 bg-near-black">
    <a class="link white-70 hover-white no-underline flex items-center pa3" href="">
        <svg
            class="dib h1 w1"
            data-icon="grid"
            viewBox="0 0 32 32"
            style="fill:currentcolor"
        >
            <title>Super Normal Icon Mark</title>
            <path d="M2 2 L10 2 L10 10 L2 10z M12 2 L20 2 L20 10 L12 10z M22 2 L30 2 L30 10 L22 10z M2 12 L10 12 L10 20 L2 20z M12 12 L20 12 L20 20 L12 20z M22 12 L30 12 L30 20 L22 20z M2 22 L10 22 L10 30 L2 30z M12 22 L20 22 L20 30 L12 30z M22 22 L30 22 L30 30 L22 30z">
            </path>
        </svg>
    </a>
    <div class="flex-grow pa3 flex items-center">
        <a class="f6 link dib white dim mr3 mr4-ns" href="#0">About</a>
        <a class="f6 link dib white dim mr3 mr4-ns" href="#0">Sign In</a>
        <a class="f6 dib white bg-animate hover-bg-white hover-black no-underline pv2 ph4 br-pill ba b--white-20" href="#0">Sign Up</a>
    </div>
</nav>


{{--<nav class="nav has-shadow">--}}
    {{--<div class="container">--}}
        {{--<div class="nav-left">--}}
            {{--<a class="nav-item">--}}
                {{--Wroclaw Events--}}
            {{--</a>--}}
            {{--<a href="{{ url('/')  }}" class="nav-item is-tab is-hidden-mobile">Home</a>--}}
        {{--</div>--}}
        {{--<span class="nav-toggle">--}}
          {{--<span></span>--}}
          {{--<span></span>--}}
          {{--<span></span>--}}
        {{--</span>--}}
        {{--<div class="nav-right nav-menu">--}}
            {{--@if (!$isLoggedIn)--}}
                {{--<a class="nav-item is-tab" href="{{ url('/login') }}">Login</a>--}}
                {{--<a class="nav-item is-tab" href="{{ url('/register') }}">Register</a>--}}
            {{--@else--}}
                {{--<a href="{{ url('/profiles/' . $user->name) }}" class="nav-item is-tab" href="#">Hi, {{ $user->name }}</a>--}}
                {{--<a class="nav-item is-tab" href="{{ url('/logout') }}"--}}
                   {{--onclick="event.preventDefault();--}}
                        {{--document.getElementById('logout-form').submit();">--}}
                    {{--Logout--}}
                {{--</a>--}}
                {{--<form id="logout-form"--}}
                      {{--action="{{ url('/logout') }}"--}}
                      {{--method="POST"--}}
                      {{--style="display: none;">--}}
                    {{--{{ csrf_field() }}--}}
                {{--</form>--}}
            {{--@endif--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</nav>--}}