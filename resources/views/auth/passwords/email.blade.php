@extends('layouts.default')

@section('content')
<div class="auth box">
    <div class="title">{{ __('Reset Password') }}</div>

    @if (session('status'))
        <div class="notification is-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="field">
            <label for="email" class="label">{{ __('E-Mail Address') }}</label>
            <input id="email" type="email" class="input @error('email') is-danger @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

            @error('email')
                <p class="help is-danger">{{ $message }}</p>
            @enderror
        </div>

        <div class="buttons">
            <button type="submit" class="button is-primary">
                {{ __('Send Password Reset Link') }}
            </button>
        </div>
    </form>
</div>
@endsection
