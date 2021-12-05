<a class="waves-effect waves-light btn-floating modal-trigger teal lighten-2" href="#visibility">
    <i class="material-icons">remove_red_eye</i>
</a>

<div id="visibility" class="modal">
    <div class="modal-content">
        <h4>{{ __('tasks.visibility') }}:
        {{-- SHOW TASK'S VISBILITY --}}
        @isset($visibility)            
            <i>{{ __('tasks.' . $visibility) }}</i>
        @endisset

        {{-- SHOW DEFAULT VALUE IF TASK CREATION --}}
        @empty($visibility)
            <i>{{ __('tasks.public') }}</i>
        @endempty 
        </h4>
        <p>{{ __('tasks.visibility-info') }}</p>
        <div class="switch">
            <label>
            {{ __('tasks.private') }}
            <input type="checkbox" name="visibility"
                {{-- INSERT TASK'S VISIBILITY VALUE --}}
                @isset($task)
                    {{ $task->visibility ? 'checked' : '' }}
                @endisset

                {{-- SET DEFAULT VALUE IF TASK CREATION --}}
                @empty($task)                
                    checked
                @endempty
            >
            <span class="lever teal lighten-2"></span>
            {{ __('tasks.public') }}
            </label>
        </div>
        <ul>
            <li><b>{{ __('tasks.public') }}</b> - {{ __('tasks.visibility-info-public') }}</li>
            <li><b>{{ __('tasks.private') }}</b> - {{__('tasks.visibility-info-private')}}</li>
        </ul>
    </div>
    <div class="modal-footer">
        <a class="modal-close waves-effect waves-light chip text-white btn modal-trigger teal lighten-2" href="#!">
            <span>{{ __('general.done') }}</span>
        </a>
    </div>
</div>