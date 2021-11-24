<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        return view('user.user-single');
    }

    public function profile() {
        
        //GET CURRENT USER
        $uid = auth()->user()->id;
        
        return $this->show($uid);
    }
}
