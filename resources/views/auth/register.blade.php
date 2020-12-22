@extends('layouts.default')

@section('content')
<div class="auth box">
    <h1 class="title">{{ __('Register') }}</h1>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="field">
            <label for="name" class="label">{{ __('Name') }}</label>
            <input id="name" type="text" class="input @error('name') is-danger @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

            @error('name')
                <p class="help is-danger" role="alert">{{ $message }}</p>
            @enderror
        </div>

        <div class="field">
            <label for="email" class="label">{{ __('E-Mail Address') }}</label>
            <input id="email" type="email" class="input @error('email') is-danger @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

            @error('email')
                <p class="help is-danger" role="alert">{{ $message }}</p>
            @enderror
        </div>

        <div class="field">
            <label for="password" class="label">{{ __('Password') }}</label>
            <input id="password" type="password" class="input @error('password') is-danger @enderror" name="password" required autocomplete="new-password">

            @error('password')
                <p class="help is-danger" role="alert">{{ $message }}</p>
            @enderror
        </div>

        <div class="field">
            <label for="password-confirm" class="label">{{ __('Confirm Password') }}</label>
            <input id="password-confirm" type="password" class="input" name="password_confirmation" required autocomplete="new-password">
        </div>

        <div class="buttons is-right">
            <button type="submit" class="button is-primary">{{ __('Register') }}</button>
        </div>
    </form>
</div>
@endsection
