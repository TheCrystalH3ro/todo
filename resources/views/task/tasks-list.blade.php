@extends('layout.app')

@section('title')
    {{__('title.task-list')}} | ToDo
@endsection

@section('main')

    <main class="task-list">

        <form action="@switch($indexType)
            @case(1)
                {{ url('user/'. $user_id . '/tasks') }}
                @break
            @case(2)
                {{ url('user/'. $user_id . '/tasks/common') }}
                @break
            @case(3)
                {{ url('user/'. $user_id . '/tasks/shared') }}
                @break
            @default
                {{ url('/tasks') }}
        @endswitch" method="GET" class="row container">
            
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

                @include('components.show-tasks')

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