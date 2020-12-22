@extends('layouts.default')

@section('content')
<div class="auth box">
    <h1 class="title">{{ __('Login') }}</h1>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="field">
            <label for="email" class="label">{{ __('E-Mail Address') }}</label>
            <input id="email" type="email" class="input is-expanded @error('email') is-danger @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

            @error('email')
                <p class="help is-danger" role="alert">{{ $message }}</p>
            @enderror
        </div>

        <div class="field">
            <label for="password" class="label">{{ __('Password') }}</label>
            <input id="password" type="password" class="input is-expanded @error('password') is-danger @enderror" name="password" required autocomplete="current-password">

            @error('password')
                <p class="help is-danger" role="alert">{{ $message }}</p>
            @enderror
        </div>

        <div class="field">
            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label for="remember">{{ __('Remember Me') }}</label>
        </div>

        <div class="buttons">
            <button type="submit" class="button is-primary">
                {{ __('Login') }}
            </button>

            @if (Route::has('password.request'))
                <a class="button is-text" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
            @endif
        </div>
    </form>
</div>
@endsection
