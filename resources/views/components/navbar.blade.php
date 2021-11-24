<ul id="userdropdown" class="dropdown-content user-dropdown">
    <li>
        <a class="profile-btn" href="">{{ __('auth.profile') }}</a>
    </li>
    <li>
        <form action="{{ url('/logout') }}" method="post">
            @csrf
            <input type="submit" class="logout-btn" value="{{ __('auth.logout')  }}">
        </form>
    </li>
</ul>

<ul id="notifdropdown" class="dropdown-content notif-dropdown">
    <li class="empty">
        <span>{{ __('general.nothing') }}</span>
    </li>
</ul>
    
    <nav class="teal lighten-2">
        <div class="nav-wrapper container">

            <ul class="left">
                <li>
                    <a class="home-btn" href="{{ url('/'); }}">{{ __('general.home') }}</a>
                </li>
                <li>
                    <a class="new-task-btn" href="{{ url('/tasks/create'); }}"> {{ __('tasks.create') }} </a>
                </li>
                <li>
                    <a class="my-tasks-btn" href="{{ url('/tasks'); }}">{{ __('tasks.myList') }}</a>
                </li>
            </ul>
            
            <ul class="right">
                <li>
                    <a class="dropdown-trigger notif-btn" data-target="notifdropdown"><i class="material-icons">notifications</i></a>
                </li>
                <li>
                    <a class="dropdown-trigger user-btn" data-target="userdropdown"><i class="material-icons">arrow_drop_down</i></a>
                </li>
            </ul>
        </div>
    </nav>