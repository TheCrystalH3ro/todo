@extends('layout.app')

@section('main')
    <main class="dashboard">
        <div class="container">

            <div class="row">
                <div class="box welcome-message col s12 blue-grey lighten-5">
                    <h5>{!! __('general.welcome', ['name' => $user->name]) !!}</h5>
                </div>
            </div>

            <div class="row dash-display">
                <div class="task-showcase col s12 m7">

                    <div class="box blue-grey lighten-5">
                       <h5>{{ __('tasks.recentTasks') }}</h5>
                    </div>
                    
                    @if ($tasks->isEmpty())

                        <div class="box task-showcase-item blue-grey lighten-5">
                            <p>
                                {{ __('tasks.noTasksUnfinished') }}
                            </p>
                            <a href="{{ url('/tasks/create') }}" class="waves-effect waves-light chip text-white btn teal lighten-2"> {{ __('tasks.createButton') }} </a>
                        </div>
                        
                    @else
                        
                        <div class="carousel">

                            @foreach ($tasks as $task)
                                
                                <div class="box task-showcase-item row blue-grey lighten-5 carousel-item">
                                    <div class="task-header col s12">
                                        @include('components.category-list')
                                    </div>
                                    <div class="task-body col s12">
                                        <div class="task-top row">
                                            @if ($task->visibility)
                                                <span>
                                                    <i class="material-icons">visibility</i>
                                                </span>
                                            @else
                                                <span>
                                                    <i class="material-icons">visibility_off</i>
                                                </span>
                                            @endif

                                            <h4 class="inline">{{ $task->name }}</h4>
                                        </div>
                                        <div class="task-detail row">
                                            <div class="col s12">
                                                <p>{{ $task->description }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="task-footer col s12 row">
                                        <div class="task-left">
                                            <a href="{{ url('/tasks/'. $task->id) }}"  class="waves-effect waves-light chip text-white btn teal lighten-2">{{ __('tasks.open') }}</a>
                                        </div>
                                        <div class="task-right">
                                            <span class="member-count">
                                                <span class="count">{{ count($task->members) }}</span>
                                                <i class="material-icons">person</i>
                                            </span>
                                            <span class="comment-count">
                                                <span class="count">{{ count($task->comments) }}</span>
                                                <i class="material-icons">comment</i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                            @endforeach

                        </div>

                        <div class="showcase-controls box blue-grey lighten-5">

                            <ul class="pagination">
                                <?php $index = 0; ?>
                                @foreach ($tasks as $task)
                                    
                                    <li class="item-{{ $index }}">
                                        <a class="{{ ($index) ? 'waves-effect' : 'active' }}" data-item="{{ $index }}"> </a>
                                    </li>

                                    <?php $index++; ?>
                                @endforeach
                            </ul>

                        </div>

                    @endif

                </div>
                <div class="sharing-box col s12 m5">

                    <div class="box blue-grey lighten-5">
                        <h5>{{ __('tasks.sharingWith') }}</h5>
                    </div>

                    @if ($members->isEmpty())

                        <div class="box shared-empty blue-grey lighten-5">
                            <p>
                                {{ __('tasks.noTasksShared') }}
                            </p>
                        </div>
                    
                    @else

                        <div class="sharing-list">
                            
                            @foreach ($members as $member)
                            
                                <div class="sharing-item box blue-grey lighten-5">
                                    <a href="{{ url('/user/'. $member->id) }}">{{ $member->name }}</a>
                                    <span>{{ $member->tasks_count . ' ' . __('tasks.tasks') }}</span>
                                </div>
                            
                            @endforeach
                            
                        </div>
                            
                    @endif

                </div>
            </div>

            <div class="row">

                <div class="box blue-grey lighten-5">
                    <h5>{{ __('tasks.sharedTasks') }}</h5>
                </div>

                <div class="shared-list">
                    @foreach ($shared as $task)
                        
                        <div class="shared-item box blue-grey lighten-5">
                            <a href="{{ url('tasks/' . $task->id) }}">
                                <div class="task-top">
                                    @if ($task->visibility)
                                        <span>
                                            <i class="material-icons">visibility</i>
                                        </span>
                                    @else
                                        <span>
                                            <i class="material-icons">visibility_off</i>
                                        </span>
                                    @endif

                                    <h6 class="inline"><b>{{ $task->name }}</b></h6>
                                </div>
                                <div class="task-body">
                                    @include('components.category-list')
                                </div>
                                <div class="task-footer">
                                    <span class="member-count">
                                        <span class="count">{{ count($task->members) }}</span>
                                        <i class="material-icons">person</i>
                                    </span>
                                    <span class="comment-count">
                                        <span class="count">{{ count($task->comments) }}</span>
                                        <i class="material-icons">comment</i>
                                    </span>
                                </div>
                            </a>
                        </div>

                    @endforeach
                </div>

            </div>

        </div>
    </main>

    <div class="fixed-action-btn">
        <a href="{{ url('tasks/create') }}" class="btn-floating btn-large teal lighten-2">
            <i class="large material-icons">add</i>
        </a>
    </div>
@endsection