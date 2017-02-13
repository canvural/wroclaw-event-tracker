@component('layouts.master')
    <div class="container">
        <div class="columns is-vcentered">
            <div class="column is-4 is-offset-4">
                <h1 class="title">
                    Login
                </h1>
                <div class="box">
                    <form role="form" method="POST" action="{{ url('/login') }}">
                        {{ csrf_field() }}
                        <label class="label" for="email">Email</label>
                        <p class="control">
                            <input class="input{{ $errors->has('email') ? ' is-danger' : '' }}" type="text" name="email" value="{{ old('email') }}" required autofocus title="email"/>
                            @if ($errors->has('email'))
                                <span class="help is-danger">{{ $errors->first('email') }}</span>
                            @endif

                        </p>
                        <label class="label">Password</label>
                        <p class="control">
                            <input class="input{{ $errors->has('password') ? ' is-danger' : '' }}" name="password" type="password" placeholder="●●●●●●●" autocomplete="off" required>
                            @if ($errors->has('password'))
                                <span class="help is-danger">{{ $errors->first('password') }}</span>
                            @endif
                        </p>
                        <p class="control">
                            <label>
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : ''}}> Remember Me
                            </label>
                        </p>
                        <hr>
                        <p class="control">
                            <button class="button is-primary">Login</button>
                            <button class="button is-default">Cancel</button>
                            <a href="{{ url('/auth/facebook') }}" class="button is-primary">
                                <span class="icon">
                                  <i class="fa fa-facebook"></i>
                                </span>
                                <span>Login with Facebook</span>
                            </a>
                        </p>
                    </form>
                </div>
                <p class="has-text-centered">
                    <a href="{{ url('/register') }}">Register an Account</a>
                    |
                    <a href="{{ url('/password/reset') }}">Forgot password</a>
                    |
                    <a href="#">Need help?</a>
                </p>
            </div>
        </div>
    </div>
@endcomponent
