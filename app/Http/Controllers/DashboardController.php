<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller {
    
    public function index() {

        if(!auth()->check()) {
            return view('welcome');
        }

        $user = User::find(Auth::id());
        
        //NUMBER OF RESULTS SHOWN
        $limit = 10;

        // MOST RECENT UNFINISHED USER'S TASKS
        $tasks = Task::whereHas('members', function ($query) {
            $query->where('users.id', Auth::id());
        })->where('status', 0)->limit($limit)->orderBy('updated_at', 'DESC')->get();

        // MOST RECENT UNFINISHED USER'S OWNED TASKS WHICH ARE SHARED
        $shared = Task::whereHas('members', function ($query) {
            $query->where('users.id', Auth::id());
            $query->where('task_user.isOwner', 1);
        })->whereHas('members', function ($query) {
            $query->where('task_user.isOwner', 0);
        })->where('status', 0)->get();

        // PEOPLE THAT HAVE COMMON TASKS WITH USER AND A NUMBER OF
        $members = User::whereHas('tasks', function ($query) {
            $query->whereHas('members', function ($subquery) {
                $subquery->where('users.id', Auth::id());
            }); 
        })->where('users.id', '!=', Auth::id())->withCount(['tasks' => function ($query) {
            $query->whereHas('members', function ($subquery) {
                $subquery->where('users.id', Auth::id());
            }); 
        }])->get();

        return view('dashboard', [
            'user' => $user,
            'members' => $members,
            'tasks' => $tasks,
            'shared' => $shared
        ]);

    }

}
