<div class="filter-box blue-grey lighten-5 col s12 m4">
                
    <div class="row">
        <div class="input-field col s12">
            <input type="text" name="task_name" id="task-name" @if ($name) value="{{ $name }}" @endif>
            <label for="task-name">{{ __('tasks.name') }}</label>
        </div>
    </div>

    <div class="row">
        <div class="input-field col s12">
            <select name="visibility">
                <option value="" @if ($visibility == NULL) selected @endif>{{ __('general.any') }}</option>
                <option value="1" @if ($visibility === "1") selected @endif>{{ __('tasks.public') }}</option>
                <option value="0" @if ($visibility === "0") selected @endif>{{ __('tasks.private') }}</option>
            </select>
            <label>{{ __('tasks.visibility') }}</label>
        </div>
    </div>

    <div class="row">
        <div class="input-field col s12">
            <select name="status">
                <option value="" @if ($status == NULL) selected @endif>{{ __('general.any') }}</option>
                <option value="1" @if ($status === "1") selected @endif>{{ __('general.yes') }}</option>
                <option value="0" @if ($status === "0") selected @endif>{{ __('general.no') }}</option>
            </select>
            <label>{{ __('tasks.completed') }}</label>
        </div>
    </div>

    <div class="row">
        <div class="input-field col s12">
            <select name="category[]" multiple>

                @foreach ($categories as $category)

                    <option value="{{ $category->id }}" {{ (is_array($category_form) && in_array($category->id, $category_form)) ? "selected" : '' }}>{{ $category->name }}</option>

                @endforeach

            </select>
            <label>{{ __('tasks.categories') }}</label>
        </div>
    </div>

    <div class="row">
        <div class="input-field col s12">
            <select name="membership">
                <option value="" @if ($membership == NULL) selected @endif>{{ __('general.any') }}</option>
                <option value="1" @if ($membership === "1") selected @endif>{{ __('tasks.owner') }}</option>
                <option value="0" @if ($membership === "0") selected @endif>{{ __('tasks.member') }}</option>
            </select>
            <label>{{ __('tasks.membership') }}</label>
        </div>
    </div>

    <div class="row">
        <div class="chips input-field col s12">
            <input class="input" name="shared_with[]" id="shared_with">
        </div>

        <div class="shared-inputs">
            @if (isset($shared_with))
                @foreach (array_filter($shared_with) as $shared)
                    <input type="hidden" name="shared_with[]" value="{{ $shared }}">
                @endforeach
            @endif
        </div>
    </div>

    <div class="row">
        <div class="input-field col s12">
            <input type="text" name="from" id="from" class="datepicker" @if ($from) value="{{ $from }}" @endif>
            <label for="from">{{ __('general.from') }}</label>
        </div>
    </div>

    <div class="row">
        <div class="input-field col s12">
            <input type="text" name="to" id="to" class="datepicker" @if ($to) value="{{ $to }}" @endif>
            <label for="to">{{ __('general.to') }}</label>
        </div>
    </div>

    <div class="row">
        <div class="input-field col s12 filter-buttons">
            <button type="submit" class="waves-effect waves-light chip text-white btn teal lighten-2"> {{ __('general.filter') }} </button>
            <a href="@switch($indexType)
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
        @endswitch"class="waves-effect waves-light chip btn"> {{ __('general.resetFilter') }} </a>
        </div>
    </div>
    
</div>