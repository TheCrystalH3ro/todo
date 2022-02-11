@extends('layout.master')

@section('content')
    <div class="verification flex flex-center banner">
        <div class="login-box container">
            <div class="box-heading">
                <h4>{{ __('auth.emailNotVerified') }}</h4>
            </div>
            <div class="box-content">
                <p>{{ __('auth.verifyEmail') }}</p>
                <form action="{{ url('/email/verification-notification') }}" method="post">
                    @csrf
                    <button type="submit" class="btn chip text-white waves-effect waves-light teal lighten-2">{{ __('auth.resendVerify') }}</button>
                </form>
            </div>
            <div class="box-footer">
                <form action="{{ url('/logout') }}" method="post">
                    @csrf
                    <input type="submit" class="text-link" value="{{ __('auth.logout')  }}">
                </form>
            </div>
        </div>
    </div>
@endsection

@section('footer')

@endsection