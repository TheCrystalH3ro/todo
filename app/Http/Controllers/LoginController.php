<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller {
    
    public function authenticate(Request $request) {

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {

            return back()->withErrors([
                'login' => __('auth.loginFailed'),
            ]);
            
        }

        $request->session()->regenerate();

        return redirect()->intended('dashboard');
    }

}
