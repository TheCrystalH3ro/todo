<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller {
    
    public function index() {

        if(!auth()->check()) {
            return view('welcome');
        }

        $user = User::find(Auth::id());

        $tasks = [];

        $shared = [];

        $members = [];

        return view('dashboard', [
            'user' => $user,
            'members' => $members,
            'tasks' => $tasks,
            'shared' => $shared
        ]);

    }

}
