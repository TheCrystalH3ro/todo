@extends('layout.master')

@section('content')
    
    <div class="flex banner flex-center">
        <div class="headline">
            <h2 class="text-white text-xl text-shadow">TODO</h2>
        </div>
        <div class="controls text-center mt-sm">
            <a href="<?= url("/login"); ?>" class="login-btn text-white m-auto text-large mb-sm z-depth-2"> <?= __('auth.login'); ?> </a>
            <span class="register-btn">
                <?= __('general.or'); ?> <a href="<?= url("/register"); ?>" class="text-white text-link"><?= __('auth.create_account'); ?></a>
            </span>
        </div>
    </div>

    <main class="about">
        <h3>About TODO</h3>

        <ul>
            <li>Create tasks</li>
            <li>Mark tasks as completed</li>
            <li>Categorize your tasks</li>
            <li>Share your tasks with others</li>
        </ul>

    </main>

@endsection