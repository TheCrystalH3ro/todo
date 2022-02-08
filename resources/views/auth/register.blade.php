@extends('layout.master')

@section('content')

    <div class="flex flex-center banner">
        <div class="login-box container">
            <div class="box-heading">
                <h4><?= __('auth.register') ?></h4>
            </div>
            <div class="box-content">

                <x-auth-validation-errors class="error-box mb-4" :errors="$errors" />

                <form action="<?= url('/register'); ?>" method="post">
                    @csrf

                    <div class="row">
                        <div class="input-field col s12">
                            <input id="username" name="name" type="text" class="validate">
                            <label for="username"><?= __('auth.username') ?></label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <input id="email" name="email" type="email" class="validate">
                            <label for="email">E-mail</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <input id="password" name="password" type="password" class="validate">
                            <label for="password"><?= __('auth.password') ?></label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <input id="confirmPassword" name="password_confirmation" type="password" class="validate">
                            <label for="confirmPassword"><?= __('auth.password_confirm') ?></label>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="input-field col s12">
                            <button class="btn chip text-white waves-effect waves-light teal lighten-2" type="submit"><?= __('auth.register') ?></button>
                        </div>
                    </div> 


                </form>
            </div>
            <div class="box-footer">
                <span>
                    <?= __('auth.have_account') ?>
                    <a href="<?= url("/login"); ?>" class="text-white text-link"><?= __('auth.login'); ?></a>
                </span>
            </div>
        </div>
    </div>

@endsection

@section('footer')
    
@endsection