@extends('layouts.default')

@section('content')
<div class="auth box">
    <div class="title">{{ __('Reset Password') }}</div>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="field">
            <label for="email" class="label">{{ __('E-Mail Address') }}</label>
            <input id="email" type="email" class="input @error('email') is-danger @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

            @error('email')
                <p class="help is-danger">{{ $message }}</p>
            @enderror
        </div>

        <div class="field">
            <label for="password" class="label">{{ __('Password') }}</label>
            <input id="password" type="password" class="input @error('password') is-danger @enderror" name="password" required autocomplete="new-password">

            @error('password')
                <p class="help is-danger">{{ $message }}</p>
            @enderror
        </div>

        <div class="field">
            <label for="password-confirm" class="label">{{ __('Confirm Password') }}</label>
            <input id="password-confirm" type="password" class="input" name="password_confirmation" required autocomplete="new-password">
        </div>

        <div class="buttons">
            <button type="submit" class="button is-primary">
                {{ __('Reset Password') }}
            </button>
        </div>
    </form>
</div>
@endsection
