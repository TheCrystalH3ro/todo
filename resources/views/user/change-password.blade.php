@extends('layout.app')

@section('title')
    {{ __('title.password-change') }} | ToDo
@endsection

@section('main')

    <main class="settings">

        <div class="settings-box container blue-grey lighten-5">

            <x-auth-validation-errors class="error-box mb-4" :errors="$errors" />

            <h3>{{ __('auth.change_password') }}</h3>

            <form action="{{ url('/change-password') }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="row">
                    <div class="input-field col s12">
                        <input id="password_old" name="password_old" type="password" class="validate">
                        <label for="password_old">{{ __('auth.password_old') }}</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12">
                        <input id="password" name="password" type="password" class="validate">
                        <label for="password">{{ __('auth.password_new') }}</label>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12">
                        <input id="confirmPassword" name="password_confirmation" type="password" class="validate">
                        <label for="confirmPassword">{{ __('auth.password_confirm') }}</label>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12">
                        <button class="btn chip text-white waves-effect waves-light teal lighten-2" type="submit">{{ __('auth.change_password') }}</button>
                    </div>
                </div> 
            </form>
        </div>

    </main>

@endsection