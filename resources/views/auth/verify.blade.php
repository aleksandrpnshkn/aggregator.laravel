@extends('layouts.default')

@section('content')
<div class="auth box">
    <h1 class="title">{{ __('Verify Your Email Address') }}</h1>

    @if (session('resent'))
        <div class="notification is-success" role="alert">
            {{ __('A fresh verification link has been sent to your email address.') }}
        </div>
    @endif

    {{ __('Before proceeding, please check your email for a verification link.') }}
    {{ __('If you did not receive the email') }},
    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
        @csrf
        <button type="submit" class="button is-text is-small">{{ __('click here to request another') }}</button>.
    </form>
</div>
@endsection
