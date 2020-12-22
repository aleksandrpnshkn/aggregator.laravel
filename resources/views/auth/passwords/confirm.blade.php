@extends('layouts.default')

@section('content')
<div class="auth box">
    <h1 class="title">{{ __('Confirm Password') }}</h1>

    {{ __('Please confirm your password before continuing.') }}

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="field">
            <label for="password" class="input">{{ __('Password') }}</label>
            <input id="password" type="password" class="input @error('password') is-danger @enderror" name="password" required autocomplete="current-password">

            @error('password')
                <p class="help is-danger" role="alert">{{ $message }}</p>
            @enderror
        </div>

        <div class="buttons">
            <button type="submit" class="button is-primary">
                {{ __('Confirm Password') }}
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
