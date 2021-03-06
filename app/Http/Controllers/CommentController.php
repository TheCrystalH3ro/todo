<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        
        $this->validate($request, [
            'task' => 'required|integer', // TASK ID
            'comment' => 'required|string' // COMMENT MESSAGE
        ]);

        $task = Task::find($request->input('task'));

        //TASK NOT FOUND
        if(!$task) {
            abort(404);
        }

        //IS TASK PRIVATE
        if(!$task->visibility) {

            //CHECK IF MEMBER WITH LOGGED USER ID EXISTS
            $isMember = User::whereHas('tasks', function ($query) use ($task) {
                $query->where('tasks.id', $task->id);
            })->where('users.id', Auth::id())->exists();

            //POSTING ALLOWED ONLY FOR MEMBERS
            if(!$isMember) {
                abort(403);
            }

        }

        $comment = new Comment();

        $comment->message = $request->input('comment');
        $comment->task_id = $task->id;
        $comment->user_id = Auth::id();

        if($comment->save()) {
            $task->updated_at = Carbon::now();

            $task->save();
        }
        
        broadcast_notification($task->members, 8, $task->id, Auth::id(), false, Auth::id());

        return redirect('tasks/'.$task->id)->with('message', __('notifications.commentCreated'))->with('status', 'success');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $task_id, $comment_id) {
        
        $this->validate($request, [
            'comment' => 'required|string' // COMMENT MESSAGE
        ]);

        $comment = Comment::find($comment_id);

        //COMMENT NOT FOUND
        if(!$comment) {
            abort(404);
        }

        //CHECK IF COMMENT BELONGS TO TASK
        $task = Task::whereHas('comments', function ($query) use ($comment_id) {
            $query->where('comments.id', $comment_id);
        })->where('tasks.id', $task_id)->first();

        if(!$task) {
            abort(403);
        }

        //CHECK IF LOGGED USER IS OWNER OF COMMENT
        $isAuthor = User::whereHas('comments', function ($query) use ($comment_id) {
            $query->where('comments.id', $comment_id);
        })->where('users.id', Auth::id())->exists();
        
        if(!$isAuthor) {

            //CHECK IF LOGGED USER IS OWNER OF TASK
            $isTaskOwner = User::whereHas('tasks', function ($query) use ($task_id) {
                $query->where('tasks.id', $task_id);
                $query->where('task_user.isOwner', 1);
            })->where('users.id', Auth::id())->exists();

            if(!$isTaskOwner) {
                abort(403);
            }

        }

        $comment->message = $request->input('comment');

        $comment->updated_at = Carbon::now();

        if($comment->save()) {
            $task->updated_at = Carbon::now();

            $task->save();
        }       

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($task_id, $comment_id) {
        
        $comment = Comment::find($comment_id);

        //COMMENT NOT FOUND
        if(!$comment) {
            abort(404);
        }

        //CHECK IF COMMENT BELONGS TO TASK
        $task = Task::whereHas('comments', function ($query) use ($comment_id) {
            $query->where('comments.id', $comment_id);
        })->where('tasks.id', $task_id)->first();

        if(!$task) {
            abort(403);
        }

        //CHECK IF LOGGED USER IS OWNER OF COMMENT
        $isAuthor = User::whereHas('comments', function ($query) use ($comment_id) {
            $query->where('comments.id', $comment_id);
        })->where('users.id', Auth::id())->exists();
        
        if(!$isAuthor) {

            //CHECK IF LOGGED USER IS OWNER OF TASK
            $isTaskOwner = User::whereHas('tasks', function ($query) use ($task_id) {
                $query->where('tasks.id', $task_id);
                $query->where('task_user.isOwner', 1);
            })->where('users.id', Auth::id())->exists();

            if(!$isTaskOwner) {
                abort(403);
            }

        }

        if($comment->delete()) {
            $task->updated_at = Carbon::now();

            $task->save();
        } 

        return redirect('tasks/'.$task_id)->with('message', __('notifications.commentDeleted'))->with('status', 'success');;

    }
}
