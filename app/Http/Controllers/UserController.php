<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

class UserController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {

        $user = User::find($id);

        $isOwner = (Auth::id() == $id);

        //STATISTICS
        $taskCount = Task::whereHas('members', function ($query) use($id) {
            $query->where('users.id', $id);
            $query->where('task_user.isOwner', 1);
        })->count();

        $finishedCount = Task::whereHas('members', function ($query) use($id) {
            $query->where('users.id', $id);
        })->where('tasks.status', 1)->count();

        //NUMBER OF RESULTS SHOWN
        $limit = 10;
        $shared = [];

        if($isOwner) {

            //MOST RECENT USER'S OWNED TASKS
            $tasks = Task::whereHas('members', function ($query) use($id) {
                $query->where('users.id', $id);
                $query->where('task_user.isOwner', 1);
            })->limit($limit)->orderBy('updated_at', 'DESC')->get();

            //MOST RECENT USER'S TASKS SHARED BY SOMEONE
            $shared = Task::whereHas('members', function ($query) use($id) {
                $query->where('users.id', $id);
                $query->where('task_user.isOwner', 0);
            })->limit($limit)->orderBy('updated_at', 'DESC')->get();

            $taskDisplayCount = Task::whereHas('members', function ($query) use($id) {
                $query->where('users.id', $id);
                $query->where('task_user.isOwner', 1);
            })->count();

            $sharedDisplayCount = Task::whereHas('members', function ($query) use($id) {
                $query->where('users.id', $id);
                $query->where('task_user.isOwner', 0);
            })->count();

        } else {

            //MOST RECENT USER'S OWNED TASKS (PUBLIC)
            $tasks = Task::whereHas('members', function ($query) use($id) {
                $query->where('users.id', $id);
                $query->where('task_user.isOwner', 1);
            })->where('tasks.visibility', 1)->limit($limit)->orderBy('updated_at', 'DESC')->get();

            //MOST RECENT USER'S TASKS SHARED BY SOMEONE
            $shared = Task::whereHas('members', function ($query) use($id) {
                $query->where('users.id', $id);
            })->whereHas('members', function ($query) {
                $query->where('users.id', Auth::id());
            })->limit($limit)->orderBy('updated_at', 'DESC')->get();

            $taskDisplayCount = Task::whereHas('members', function ($query) use($id) {
                $query->where('users.id', $id);
                $query->where('task_user.isOwner', 1);
            })->where('tasks.visibility', 1)->count();

            $sharedDisplayCount = Task::whereHas('members', function ($query) use($id) {
                $query->where('users.id', $id);
            })->whereHas('members', function ($query) use($id) {
                $query->where('users.id', Auth::id());
            })->count();

        }

        return view('user.user-single', [
            'user' => $user,
            'tasks' => $tasks,
            'shared' => $shared,
            'taskCount' => $taskCount,
            'finishedCount' => $finishedCount,
            'limit' => $limit,
            'isOwner' => $isOwner,
            'isEdit' => false,
            'taskDisplayCount' => $taskDisplayCount,
            'sharedDisplayCount' => $sharedDisplayCount
        ]);
    }

    public function profile(Request $request) {
        
        //GET CURRENT USER
        $uid = auth()->user()->id;
        
        return $this->show($request, $uid);
    }

    public function edit() {

        return view('user.change-password');

    }

    public function update(Request $request) {
        
        $this->validate($request, [
            'password_old' => ['required'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::find(Auth::id());

        //CHECK IF PASSWORD ENTERED MATCHES USER'S
        if (!Hash::check($request->input('password_old'), $user->password)) {
            $validator = Validator::make([], [])->errors()->add('password_old', __('auth.wrong_password'));
            return back()->withErrors($validator);
        }

        //CHANGE PASSWORD
        $user = User::find(Auth::id());
        $user->password = bcrypt($request->get('password'));
        $user->save();

        return redirect('/profile')->with('success', __('auth.password_changed'));

    }
}
