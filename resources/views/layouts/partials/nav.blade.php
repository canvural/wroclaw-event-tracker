<nav class="nav has-shadow">
    <div class="container">
        <div class="nav-left">
            <a class="nav-item">
                Wroclaw Events
            </a>
            <a href="{{ url('/')  }}" class="nav-item is-tab is-hidden-mobile">Home</a>
        </div>
        <span class="nav-toggle">
          <span></span>
          <span></span>
          <span></span>
        </span>
        <div class="nav-right nav-menu">
            @if (!$isLoggedIn)
                <a class="nav-item is-tab" href="{{ url('/login') }}">Login</a>
                <a class="nav-item is-tab" href="{{ url('/register') }}">Register</a>
            @else
                <a href="{{ url('/profiles/' . $user->name) }}" class="nav-item is-tab" href="#">Hi, {{ $user->name }}</a>
                <a class="nav-item is-tab" href="{{ url('/logout') }}"
                   onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                    Logout
                </a>
                <form id="logout-form"
                      action="{{ url('/logout') }}"
                      method="POST"
                      style="display: none;">
                    {{ csrf_field() }}
                </form>
            @endif
        </div>
    </div>
</nav>