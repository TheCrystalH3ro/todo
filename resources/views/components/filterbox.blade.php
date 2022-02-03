<div class="filter-box blue-grey lighten-5 col s12 m4">
                
    <div class="row">
        <div class="input-field col s12">
            <input type="text" name="task_name" id="task-name">
            <label for="task_name">{{ __('tasks.name') }}</label>
        </div>
    </div>

    <div class="row">
        <div class="input-field col s12">
            <select name="visibility">
                <option value="" selected>{{ __('general.any') }}</option>
                <option value="1">{{ __('tasks.public') }}</option>
                <option value="0">{{ __('tasks.private') }}</option>
            </select>
            <label>{{ __('tasks.visibility') }}</label>
        </div>
    </div>

    <div class="row">
        <div class="input-field col s12">
            <select name="status">
                <option value="" selected>{{ __('general.any') }}</option>
                <option value="1">{{ __('general.yes') }}</option>
                <option value="0">{{ __('general.no') }}</option>
            </select>
            <label>{{ __('tasks.completed') }}</label>
        </div>
    </div>

    <div class="row">
        <div class="input-field col s12">
            <select name="category[]" multiple>

                @foreach ($categories as $category)

                    <option value="{{ $category->id }}">{{ $category->name }}</option>

                @endforeach

            </select>
            <label>{{ __('tasks.categories') }}</label>
        </div>
    </div>

    <div class="row">
        <div class="input-field col s12">
            <select name="membership">
                <option value="" selected>{{ __('general.any') }}</option>
                <option value="1">{{ __('tasks.owner') }}</option>
                <option value="0">{{ __('tasks.member') }}</option>
            </select>
            <label>{{ __('tasks.membership') }}</label>
        </div>
    </div>

    <div class="row">
        <div class="input-field col s12">
            <input type="text" name="shared-with[]" id="shared-with">
            <label id="shared-with">{{ __('tasks.sharedWith') }}</label>
        </div>
    </div>

    <div class="row">
        <div class="input-field col s12">
            <input type="text" name="created_at" id="created_at" class="datepicker">
            <label id="created_at">{{ __('general.created_at') }}</label>
        </div>
    </div>

    <div class="row">
        <div class="input-field col s12">
            <button type="submit" class="waves-effect waves-light chip text-white btn teal lighten-2"> {{ __('general.filter') }} </button>
        </div>
    </div>
    
</div>