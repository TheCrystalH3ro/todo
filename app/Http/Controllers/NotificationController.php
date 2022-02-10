<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller {
    
    public function clear($id) {

        $notification = Notification::find($id);

        if(!$notification) {
            abort(404);
        }

        if($notification->user_id != Auth::id()) {
            abort(403);
        }

        $notification->delete();

        return redirect()->back();

    }

}
