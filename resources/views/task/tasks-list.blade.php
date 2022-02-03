@extends('layout.app')

@section('main')

    <main class="task-list">

        <form action="{{ url('/tasks') }}" method="GET" class="row container">
            
            @include('components.filterbox')

            <div class="result col s12 m8">

                <div class="filter-sort-by blue-grey lighten-5 z-depth-3">

                    <div class="input-field">
                        <select name="sort_by">
                            <option value="0" selected>{{ __('general.recent') }}</option>
                            <option value="1">{{ __('tasks.name') }}</option>
                            <option value="1">{{ __('tasks.memberCount') }}</option>
                            <option value="1">{{ __('tasks.commentCount') }}</option>
                            <option value="1">{{ __('general.created_at') }}</option>
                        </select>
                        <label>{{ __('general.sort_by') }}</label>
                    </div>

                    <div class="input-field">
                        <input type="hidden" name="order" value="0">
                        <button class="order">
                            <i class="material-icons">keyboard_arrow_down</i>
                            <b>{{ __('general.descending') }}</b>
                        </button>
                    </div>
                        
                </div>

                <div class="list">

                    @foreach ($tasks as $task)
                        
                        <a href="{{ url('tasks/' . $task->id) }}">
                            <div class="task-item blue-grey lighten-5">

                                <div class="task-header">
                                    <div class="task-left">
                                        @if ($task->visibility)
                                            <span>
                                                <i class="material-icons">visibility</i>
                                            </span>
                                        @else
                                            <span>
                                                <i class="material-icons">visibility_off</i>                                            </span>
                                            </span>
                                        @endif

                                        <h6 class="inline">{{ $task->name }}</h6>
                                    </div>
                                    <div class="task-right">
                                        @include('components.task-isdone')
                                    </div>
                                </div>

                                <div class="task-body">
                                    <div class="task-left">
                                        <span class="member-count">
                                            <span class="count">{{ count($task->members) }}</span>
                                            <i class="material-icons">person</i>
                                        </span>
                                        <span class="comment-count">
                                            <span class="count">{{ count($task->comments) }}</span>
                                            <i class="material-icons">comment</i>
                                        </span>
                                    </div>
                                    <div class="task-right">
                                        @include('components.category-list')
                                    </div>
                                </div>

                            </div>
                        </a>

                    @endforeach

                </div>

                <ul class="pagination blue-grey lighten-5 z-depth-3">
                    <li class="disabled"><a href="#!"><i class="material-icons">chevron_left</i></a></li>
                    <li class="page active"><a> 1 </a></li>
                    <li class="page waves-effect"> 
                        <a href="{{ url('/tasks?page=' . 2) }}">2</a>
                    </li>
                    <li class="page waves-effect"> 
                        <a href="{{ url('/tasks?page=' . 3) }}">3</a>
                    </li>
                    <li class="page waves-effect"> 
                        <a href="{{ url('/tasks?page=' . 4) }}">4</a>
                    </li>
                    <li class="page disabled">
                        <a>...</a>
                    </li>
                    <li class="page waves-effect"> 
                        <a href="{{ url('/tasks?page=' . 8) }}">8</a>
                    </li>
                    <li class="page waves-effect"> 
                        <a href="{{ url('/tasks?page=' . 9) }}">9</a>
                    </li>
                    <li class="waves-effect"><a href="#!"><i class="material-icons">chevron_right</i></a></li>

                </div>

            </div>
            
        </form>

    </main>

@endsection