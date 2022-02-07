<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Notification;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MembersController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        
        $this->validate($request, [
            'task' => 'required|integer',
            'username' => 'required|string'
        ]);

        $task_id = $request->input('task');

        $task = Task::find($task_id);

        //TASK NOT FOUND
        if(!$task) {
            abort(404);
        }

        //CHECK IF LOGGED USER IS OWNER OF TASK
        $isOwner = User::whereHas('tasks', function ($query) use ($task_id) {
            $query->where('tasks.id', $task_id);
            $query->where('task_user.isOwner', 1);
        })->where('users.id', Auth::id())->exists();

        if(!$isOwner) {
            abort(403);
        }

        $user = User::where('users.name', $request->input('username'))->first();

        //USER NOT FOUND
        if(!$user) {
            abort(404);
        }

        //CHECK IF USER IS ALREADY A MEMBER
        $isMember = User::whereHas('tasks', function ($query) use ($task) {
            $query->where('tasks.id', $task->id);
        })->where('users.id', $user->id)->exists();

        if($isMember) {
            return redirect('tasks/'.$task->id); 
        }

        //CHECK IF USER ALREADY HAS INVITATION FOR THIS TASK
        $isInvited = Invitation::where('task_id', $task->id)->where('user_id', $user->id)->exists();

        if($isInvited) {
            return redirect('tasks/'.$task->id); 
        }

        $invite = new Invitation();

        $invite->task_id = $task->id;
        $invite->user_id = $user->id;

        $invite->save();

        $notification = new Notification();

        $notification->type = 0;

        $notification->sender_id = Auth::id();

        $notification->task_id = $task->id;

        $notification->user_id = $user->id;

        $notification->external_id = $invite->id;

        $notification->save();

        return redirect('tasks/'.$task->id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($task_id, $user_id) {
        
        $task = Task::find($task_id);

        //TASK NOT FOUND
        if(!$task) {
            abort(404);
        }

        //GET TASK OWNER
        $task->owner = User::whereHas('tasks', function ($query) use ($task_id) {
            $query->where('tasks.id', $task_id);
            $query->where('task_user.isOwner', 1);
        })->first();

        //CHECK IF LOGGED USER IS OWNER OF TASK
        $isOwner = ($task->owner) ? ($task->owner->id == Auth::id()) : false;

        if(!$isOwner && $user_id != Auth::id()) {
            abort(403);
        }

        //OWNER CAN'T BE REMOVED
        if($user_id == $task->owner->id) {
            abort(403);
        }

        $user = User::find($user_id);

        //USER NOT FOUND
        if(!$user) {
            abort(404);
        }

        $task->members()->detach($user_id);

        $task->updated_at = Carbon::now();

        unset($task->owner);

        $task->save();

        if(Auth::id() == $user_id) {

            return redirect('tasks');

        }

        return redirect('tasks/'.$task_id);

    }
}
