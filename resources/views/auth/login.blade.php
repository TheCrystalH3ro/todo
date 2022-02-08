@extends('layout.master')

@section('content')

    <div class="flex flex-center banner">
        <div class="login-box container">
            <div class="box-heading">
                <h4><?= __('auth.login') ?></h4>
            </div>
            <div class="box-content">

                <x-auth-validation-errors class="error-box mb-4" :errors="$errors" />

                <form action="<?= url('/login'); ?>" method="post">
                    @csrf

                    <div class="row">
                        <div class="input-field col s12">
                            <input placeholder="<?= __('auth.username') . ' ' . __('general.or') . ' e-mail' ?>" id="username" name="login" type="text" class="validate">
                            <label for="username"><?= __('auth.userLogin') ?></label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <input id="password" type="password" name="password" class="validate">
                            <label for="password"><?= __('auth.password') ?></label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s-12">
                            <p class="input-check">
                                <label for="rememberMe">
                                    <input type="checkbox" name="remember" id="rememberMe" class="filled-in">
                                    <span><?= __('auth.rememberMe') ?></span>
                                </label>
                            </p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="input-field col s12">
                            <button class="btn chip text-white waves-effect waves-light teal lighten-2" type="submit"><?= __('auth.login') ?></button>
                        </div>
                    </div> 


                </form>
            </div>
            <div class="box-footer">
                <span class="register-btn">
                    <?= __('auth.no_account') ?>
                    <a href="<?= url("/register"); ?>" class="text-white text-link"><?= __('auth.create_account'); ?></a>
                </span>
            </div>
        </div>
    </div>

@endsection

@section('footer')
    
@endsection