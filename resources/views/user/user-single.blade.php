@extends('layout.app')

@section('main')
    <main class="user">
        <div class="user-box container blue-grey lighten-5">
            <div class="user-top">
                <h3>{{  $user->name }}</h3> 
                @if ($isOwner)             
                    <div class="user-button">
                        <a href="{{ url('change-password') }}" class="waves-effect waves-light chip text-white btn teal lighten-2">
                            {{ __('auth.changePassword') }}
                        </a>
                    </div>     
                @endif
            </div>
            <p>
                @if ($isOwner)
                    <h6><b> {{ $user->email }} </b></h6>
                @endif
                <h6><b>{{ __('stats.accountCreated') }}:</b> {{ Carbon\Carbon::parse($user->created_at)->diffForHumans() }}</h6>
            </p>
            <ul class="user-stats">
                <li><b>{{ __('stats.tasksCreated') }}:</b> {{ $taskCount }}</li>
                <li><b>{{ __('stats.tasksCompleted') }}:</b> {{ $finishedCount }}</li>
            </ul>
        </div>

        <div class="tasks container row">
            <div class="tasks-list col s12 m7">
                <div class="list-header blue-grey lighten-5"">
                    <h5>{{ __('tasks.tasks') }}</h5>
                </div>
                <div class="list">

                    @foreach ($tasks as $task)
                                            
                        @include('components.task-card')
                
                    @endforeach 
                    
                </div>

                @if ($taskDisplayCount > $limit)                
                    <div class="list-footer blue-grey lighten-5">
                        <a href="{{ url('user/'.$user->id . '/tasks') }}">{{ __('tasks.seeMore') }}</a>
                    </div>
                @endif
            </div>

            <div class="shared-tasks col s12 m5">
                <div class="list-header blue-grey lighten-5"">
                    <h5>{{ __('tasks.sharedTasks') }}</h5>
                </div>
                <div class="list">

                    @foreach ($shared as $task)
                                            
                        @include('components.task-card')
                
                    @endforeach 
                    
                </div>

                @if ($sharedDisplayCount > $limit)                
                    <div class="list-footer blue-grey lighten-5">
                        <a href="@if ($isOwner) {{ url('user/'.$user->id . '/tasks/shared') }} @else {{ url('user/'.$user->id . '/tasks/common') }} @endif">{{ __('tasks.seeMore') }}</a>
                    </div>
                @endif
            </div>
        </div>
    </main>
@endsection