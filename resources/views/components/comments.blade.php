<div class="comments container blue-grey lighten-5">

    <form action="{{ url('tasks/' . $task->id . '/comments') }}" method="post">
        @csrf
        @method('post')
    
        <input type="hidden" name="task" value="{{ $task->id }}">

        <div class="row">
            <h4> {{ __('tasks.comments') }} </h4>
        </div>

        <div class="message-input row">
            <div class="input-field inline col s12">
                <textarea name="comment" id="comment-box" class="materialize-textarea"></textarea>
                <label for="comment-box"> {{ __('tasks.comment') }} </label>
            </div>

            <div class="input-field inline">
                            
                <button type="submit" class="waves-effect waves-teal btn-flat text-teal text-lighten-2">
                    <i class="material-icons">send</i>
                </button>

            </div>
        </div>

    </form>

    <div class="comment-list">

        @foreach ($task->comments as $comment)
            
            <div id="comment-{{ $comment->id }}" class="comment row">

                <div class="comment-header">
                    
                    <h6>
                        <a href="{{ url('/user/' . $comment->user_id) }}">
                            {{ $comment->user->name }}
                        </a>
                    </h6>

                    <span class="comment-time">
                        {{ Carbon\Carbon::parse($comment->created_at)->diffForHumans()  }}
                    </span>

                </div>

                <div class="comment-body">

                    {{ $comment->message }}

                </div>

                <div class="comment-footer">

                    @if ($comment->user_id == Auth::id())
                        <button class="edit-btn" data-comment="{{ $comment->id }}">Edit</button> <span> | </span>
                    @endif

                    @if ($comment->user_id == Auth::id() || $isOwner)
                        <form action="{{ url('tasks/' . $task->id . '/comments/' . $comment->id) }}" method="post" class="inline">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="delete-btn"> Delete </button>

                        </form>
                    @endif

                </div>

            </div>

        @endforeach

    </div>

</div>