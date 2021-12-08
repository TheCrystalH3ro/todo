<form action="" method="post">
    @csrf

    <input type="hidden" name="task" value="{{ $task->id }}">
    
    <div class="comments">

        <div class="row">
            <h4> {{ __('tasks.comments') }} </h4>
        </div>

        <div class="row">
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

        <div class="comment-list">

            @foreach ($task->comments as $comment)
                
                <div class="comment row">

                    <div class="comment-header">
                        
                        <h6>
                            <a href="{{ url('/user/' . $comment->user->id) }}">
                                {{ $comment->user->name }}
                            </a>
                        </h6>

                    </div>

                    <div class="comment-body">

                        {{ $comment->message }}

                    </div>

                    <div class="comment-footer">

                        {{ Carbon::parse($comment->created_at)->diffForHumans()  }}

                    </div>

                </div>

            @endforeach

        </div>

    </div>

</form>