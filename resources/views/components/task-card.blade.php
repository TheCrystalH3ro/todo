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
                        <i class="material-icons">visibility_off</i>
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