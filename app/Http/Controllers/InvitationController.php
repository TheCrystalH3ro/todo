<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Notification;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvitationController extends Controller {
    
    public function accept($id) {

        $invite = Invitation::find($id);

        if(!$invite) {
            abort(404);
        }

        if($invite->user_id != Auth::id()) {
            abort(403);
        }

        //REMOVE INVITATION NOTIFICATION
        $notification = Notification::where('type', 0)->where('external_id', $id);

        if($notification) {
            $notification->delete();
        }

        $task = Task::find($invite->task_id);
        
        $invite->delete();

        if(!$task) {
            abort(404);
        }

        //CHECK IF USER IS ALREADY A MEMBER
        $isMember = User::whereHas('tasks', function ($query) use ($task) {
            $query->where('tasks.id', $task->id);
        })->where('users.id', Auth::id())->exists();

        if($isMember) {
            return redirect('tasks/'.$task->id)->with('message', __('notifications.memberAlreadyJoined'))->with('status', 'failure');
        }

        foreach($task->members as $member) {
            send_notification(3, $task->id, $member->id, Auth::id());
        }

        $task->members()->attach(Auth::id(), ['isOwner' => 0]);

        $task->updated_at = Carbon::now();

        $task->save();

        return redirect('tasks/'.$task->id)->with('message', __('notifications.taskJoined', ['task' => $task->name]))->with('status', 'success');

    }

    public function decline($id) {

        $invite = Invitation::find($id);

        if(!$invite) {
            abort(404);
        }

        if($invite->user_id != Auth::id()) {
            abort(403);
        }

        //REMOVE INVITATION NOTIFICATION
        $notification = Notification::where('type', 0)->where('external_id', $id);

        if($notification) {
            $notification->delete();
        }

        $invite->delete();

        return redirect()->back()->with('message', __('notifications.taskDeclined'))->with('status', 'success');

    }

}
