<ul id="userdropdown" class="dropdown-content user-dropdown">
    <li>
        <a class="profile-btn" href="{{ url('/profile') }}">{{ __('auth.profile') }}</a>
    </li>
    <li>
        <form action="{{ url('/logout') }}" method="post">
            @csrf
            <input type="submit" class="logout-btn" value="{{ __('auth.logout')  }}">
        </form>
    </li>
</ul>

<ul id="notifdropdown" class="dropdown-content notif-dropdown">
    <?php $notifications = App\Models\Notification::with('sender', 'task')->where('user_id', Auth::id())->get(); ?>
    @if ($notifications->isEmpty())
        <li class="empty">
            <span>{{ __('general.nothing') }}</span>
        </li>
    @else
        @foreach($notifications as $notification)

            <li class="notification">
                
                @switch($notification->type)
                    @case(0)
                        <p> {!! __('notifications.invitation', ['user' => $notification->sender->name, 'task' => $notification->task->name]) !!} </p>
                        <div class="controls">
                            <a href="{{ url('/invite/' . $notification->external_id . '/accept') }}" class="modal-close waves-effect waves-light chip text-white btn teal lighten-2">{{ __('general.accept') }}</a>
                            <a href="{{ url('/invite/' . $notification->external_id . '/decline') }}" class="waves-effect waves-red chip btn">{{ __('general.decline') }}</a>
                        </div>
                        @break
                    @default
                        <p></p>
                @endswitch

            </li>

        @endforeach
    @endif
    
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
                    <a class="dropdown-trigger notif-btn" data-target="notifdropdown"><i class="material-icons">notifications</i>@if(count($notifications) > 0)<span class="notif-circle"></span><span class="notif-count">{{ count($notifications) }}</span>@endif</a>
                </li>
                <li>
                    <a class="dropdown-trigger user-btn" data-target="userdropdown"><i class="material-icons">arrow_drop_down</i></a>
                </li>
            </ul>
        </div>
    </nav>