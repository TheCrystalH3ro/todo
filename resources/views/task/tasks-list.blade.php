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
                    <li class="{{ $page == 1 ? 'disabled' : "waves-effect"}}">
                        <a {{ $page != 1 ? ( 'href='. ($has_page ? ( str_replace("page=$page", 'page='. ($page - 1), $page_url) ) : ( $page_url . 'page='. ($page - 1) ) ) ) : ''}}>
                            <i class="material-icons">chevron_left</i>
                        </a>
                    </li>

                    @foreach ($pages as $page_i)

                        @if ($page_i === '...')
                            <li class="page disabled">
                                <a>...</a>
                            </li>

                            @continue
                        @endif
                        
                        <li class="page {{ ($page_i == $page) ? 'active' : 'waves-effect'  }}"> 
                            <a {{ $page_i != $page ? ( 'href='. ($has_page ? ( str_replace("page=$page", 'page='. ($page_i), $page_url) ) : ( $page_url . 'page='. ($page_i) ) ) ) : ''}}>{{ $page_i }}</a>
                        </li>

                    @endforeach

                    <li class="{{ $page == $last_page ? 'disabled' : "waves-effect"}}">
                        <a {{ $page != $last_page ? ( 'href='. ($has_page ? ( str_replace("page=$page", 'page='. ($page + 1), $page_url) ) : ( $page_url . 'page='. ($page + 1) ) ) ) : ''}}>
                            <i class="material-icons">chevron_right</i>
                        </a>
                    </li>

                </div>

            </div>
            
        </form>

    </main>

@endsection