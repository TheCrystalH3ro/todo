<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $tasks = Task::whereHas('members', function ($query) {
            $query->where('users.id', Auth::id());
        })->get();

        $categories = Category::all();

        return view('task.tasks-list', [
            'tasks' => $tasks,
            'categories' => $categories,
            'isEdit' => false
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

        $categories = Category::all();
        
        return view('task.task-new', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $this->validate($request, [
            'task_name' => 'required|string', // NAME OF TASK
            'task_description' => 'string', // TASK DESCRIPTION
            'add-category' => 'array', // ARRAY OF CATEGORY IDs
            'add-category.*' => 'integer'
        ]);

        $task = new Task();

        $task->name = $request->input('task_name');
        $task->description = $request->input('task_description');
        $task->visibility = ($request->input('visibility') && 'on') ? 1 : 0;
        $task->status = 0;

        if($task->save()) {

            $task->categories()->sync($request->input('add-category'));
            $task->members()->attach(Auth::id(), ['isOwner' => 1]);

        }

        return redirect('tasks/'.$task->id);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {

        $task = Task::with('categories', 'members', 'comments')->find($id);

        //TASK NOT FOUND
        if(!$task) {
            abort(404);
        }

        $task->owner = User::whereHas('tasks', function ($query) use ($id) {
            $query->where('tasks.id', $id);
            $query->where('task_user.isOwner', 1);
        })->first();

        $isMember = $task->members()->where('users.id', Auth::id())->exists();

        //DISPLAY ONLY IF TASK IS PUBLIC OR USER IS A MEMBER
        if(!$task->visibility && !$isMember) {
            abort(403);
        }

        return view('task.task-single', [
            'task' => $task,
            'isEdit' => false,
            'isOwner' => ($task->owner) ? ($task->owner->id == Auth::id()) : false,
            'isMember' => $isMember,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {

        $task = Task::find($id);

        //TASK NOT FOUND
        if(!$task) {
            abort(404);
        }

        //CHECK IF MEMBER WITH LOGGED USER ID EXISTS
        $isMember = User::whereHas('tasks', function ($query) use ($id) {
            $query->where('tasks.id', $id);
        })->where('users.id', Auth::id())->exists();

        //EDITING ALLOWED ONLY FOR MEMBERS
        if(!$isMember) {
            abort(403);
        }

        //GET ALL UNSELECTED CATEGORIES
        $categories = Category::whereDoesntHave('Tasks', function ($query) use($id) {
                        $query->where('tasks.id', $id);
                    })->get();
        
        return view('task.task-edit', [
            'task' => $task,
            'categories' => $categories,
            'isEdit' => true
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        $this->validate($request, [
            'task_name' => 'required|string', // NAME OF TASK
            'task_description' => 'string', // TASK DESCRIPTION
            'add-category' => 'array', // ARRAY OF CATEGORY IDs
            'add-category.*' => 'integer'
        ]);
        
        $task = Task::find($id);

        //TASK NOT FOUND
        if(!$task) {
            abort(404);
        }

        //CHECK IF MEMBER WITH LOGGED USER ID EXISTS
        $isMember = User::whereHas('tasks', function ($query) use ($id) {
            $query->where('tasks.id', $id);
        })->where('users.id', Auth::id())->exists();

        //EDITING ALLOWED ONLY FOR MEMBERS
        if(!$isMember) {
            abort(403);
        }

        $task->name = $request->input('task_name');
        $task->description = $request->input('task_description');
        $task->visibility = ($request->input('visibility') && 'on') ? 1 : 0;
        $task->status = ($request->input('status') && 'on') ? 1 : 0;;

        $task->categories()->sync($request->input('add-category'));

        $task->updated_at = Carbon::now();

        $task->save();

        return redirect('tasks/'.$task->id);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        
        $task = Task::with('comments')->find($id);

        //TASK NOT FOUND
        if(!$task) {
            abort(404);
        }

        //CHECK IF LOGGED USER IS OWNER OF TASK
        $isOwner = User::whereHas('tasks', function ($query) use ($id) {
            $query->where('tasks.id', $id);
            $query->where('task_user.isOwner', 1);
        })->where('users.id', Auth::id())->exists();

        if(!$isOwner) {
            abort(403);
        }

        $task->categories()->detach();
        $task->members()->detach();

        $task->removeRelation($task->comments);

        $task->delete();

        return redirect('tasks');

    }
}
