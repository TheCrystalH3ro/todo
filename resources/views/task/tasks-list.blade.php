@extends('layout.app')

@section('main')

    <main class="task-list">

        <form action="{{ url('/tasks') }}" method="GET" class="row container">
            
            @include('components.filterbox')

            <div class="result col s12 m8">

                <div class="filter-sort-by blue-grey lighten-5 z-depth-3">

                    <div class="input-field">
                        <select name="sort_by">
                            <option value="0" @if (!$sort_by) selected @endif>{{ __('general.recent') }}</option>
                            <option value="1" @if ($sort_by === "1") selected @endif>{{ __('tasks.name') }}</option>
                            <option value="2" @if ($sort_by === "2") selected @endif>{{ __('tasks.memberCount') }}</option>
                            <option value="3" @if ($sort_by === "3") selected @endif>{{ __('tasks.commentCount') }}</option>
                            <option value="4" @if ($sort_by === "4") selected @endif>{{ __('general.created_at') }}</option>
                        </select>
                        <label>{{ __('general.sort_by') }}</label>
                    </div>

                    <div class="input-field">
                        <input type="hidden" name="order" value="{{ $order }}">
                        <button class="order">
                            @if ($order)
                                <i class="material-icons">keyboard_arrow_up</i>
                                <b>{{ __('general.ascending') }}</b>
                            @else    
                                <i class="material-icons">keyboard_arrow_down</i>
                                <b>{{ __('general.descending') }}</b>
                            @endif
                        </button>
                    </div>
                        
                </div>

                <div class="list">

                    @include('components.show-tasks')

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

    <script>
        let filterLang = {
            descending: "{{ __('general.descending') }}",
            ascending: "{{ __('general.ascending') }}"
        };
    </script>

@endsection