<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class TaskController extends Controller
{

    private function pagination($page, $page_count) {

        //PAGE NUMBERS TO DISPLAY
        $resultPages = [1, 2, $page - 2, $page - 1, $page, $page + 1, $page + 2, $page_count - 1, $page_count];

        $pages = [];

        foreach($resultPages as $result) {

            //DON'T SHOW DUPLICATES
            if(in_array($result, $pages)) {
               continue; 
            }

            //SKIP NUMBERS OUTSIDE OF RANGE
            if($result < 1 || $result > $page_count) {
                continue;
            }

            //IF PAGES ARE NOT NEXT TO EACH OTHER ADD '...'
            if($result != 1 && !in_array($result - 1, $pages)) {
                $pages[] = '...';
            }

            $pages[] = $result;

        }

        return $pages;

    }

    private function getSorting($sorting) {

        switch($sorting) {

            case 1:
                return 'name';
            case 2:
                return 'members_count';
            case 3:
                return 'comments_count';
            case 4:
                return 'created_at';
            default:
                return 'updated_at';

        }

    }

    private function setOrderBy($tasks, $sorting, $order) {

        switch($sorting) {
            case 'members_count':
                $tasks = $tasks->withCount('members');
                break;
            case 'comments_count':
                $tasks = $tasks->withCount('comments');
                break;
        }

        if($order) {
            return $tasks->orderBy($sorting);
        } 

        return $tasks->orderBy($sorting, 'DESC');

    }

    private function getFilters() {

        return [
            'task_name',
            'visibility',
            'status',
            'category',
            'membership',
            'shared_with',
            'from',
            'to'
        ];

    }

    private function setFilters($tasks, $request) {

        foreach($this->getFilters() as $filter) {


            if($request->input($filter) === false || $request->input($filter) === NULL) {
                continue;
            }

            switch($filter) {
                case 'task_name':
                    $tasks = $tasks->where('name', 'LIKE', '%'. $request->input($filter) .'%');
                    break;
                case 'category':
                    $categories = $request->input($filter);
                    $tasks = $tasks->whereHas('categories', function ($query) use ($categories) {
                        $query->whereIn('categories.id', $categories);
                    });
                    break;
                case 'membership':
                    $membership = $request->input($filter);
                    $tasks = $tasks->whereHas('members', function ($query) use ($membership) {
                        $query->where('users.id', Auth::id());
                        $query->where('task_user.isOwner', $membership);
                    });
                    break;
                case 'shared_with':
                    $members = $request->input($filter);
                    $tasks = $tasks->whereHas('members', function ($query) use ($members) {

                        $index = 0;
                        foreach($members as $member) {

                            if(!$index) {
                                $query->where('users.name', 'LIKE', "%$member%");
                                $index++;
                                continue;
                            }

                            $query->orWhere('users.name', 'LIKE', "%$member%");
                            $index++;
                        }

                    });
                    break;
                case 'from':
                    $from = date('Y-m-d 00:00:00', strtotime($request->input($filter)));
                    $tasks = $tasks->where('created_at', '>=', $from );
                    break;
                case 'to':
                    $to = date('Y-m-d 23:59:59', strtotime($request->input($filter)));
                    $tasks = $tasks->where('created_at', '<=', $to );
                    break;
                default:
                    $tasks = $tasks->where($filter, $request->input($filter));
                    break;
            }          

        }

        return $tasks;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {

        $this->validate($request, [
            'task_name' => 'string|nullable',
            'visibility' => 'boolean|nullable',
            'status' => 'boolean|nullable',
            'category' => 'array|nullable',
            'category.*' => 'integer|nullable',
            'membership' => 'boolean|nullable',
            'shared_with' => 'array|nullable',
            'shared_with.*' => 'string|nullable',
            'from' => 'date|nullable',
            'to' => 'date|nullable',
            'sort_by' => 'integer|nullable',
            'order' => 'boolean|nullable',
            'isAjax' => 'boolean',
            'page_url' => 'string',
        ]);

        //GET FILTERED TASKS
        $tasks = Task::whereHas('members', function ($query) {
            $query->where('users.id', Auth::id());
        });

        $sort_by = $this->getSorting($request->input('sort_by'));

        $tasks = $this->setOrderBy($tasks, $sort_by, $request->input('order'));

        $tasks = $this->setFilters($tasks, $request);

        //NUMBER OF RESULTS PER PAGE
        $limit = 10;

        //GET RESULT COUNT
        $results = $tasks->count() ?: 1;

        //NUMBER OF PAGES
        $page_count = ceil($results / $limit);

        //VALIDATE PAGE COUNT
        $this->validate($request, [
            'page' => "integer|min:1|max:$page_count",
        ]);

        //CURRENT PAGE
        $page = $request->query('page', 1);

        $page_url = URL::full();

        $view = 'task.tasks-list';

        if($request->input('isAjax')) {
            $page_url = $request->input('page_url');
            $view = 'components.show-tasks';
        }
        
        if(!$request->input('page')) {

            $hasQuery = (URL::current() !== URL::full());

            $page_url .=  $hasQuery ? '&' : '?' ;

        }

        $tasks = $tasks->limit($limit)->offset(($page - 1)  * $limit)->get();

        $categories = Category::all();

        return view($view, [
            'tasks' => $tasks,
            'categories' => $categories,
            'page' => $page,
            'pages' => $this->pagination($page, $page_count),
            'last_page' => $page_count,
            'has_page' => ($request->input('page') != false),
            'page_url' => $page_url,
            'name' => $request->input('task_name'),
            'visibility' => $request->input('visibility'),
            'status' => $request->input('status'),
            'category_form' => $request->input('category'),
            'membership' => $request->input('membership'),
            'shared_with' => $request->input('shared_with'),
            'from' => $request->input('from'),
            'to' => $request->input('to'),
            'sort_by' => $request->input('sort_by'),
            'order' => $request->query('order', 0),
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
