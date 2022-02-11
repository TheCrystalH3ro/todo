<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Notification extends Model {
    
    use HasFactory;
    
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function sender() {
        return $this->belongsTo(User::class);
    }

    public function task() {
        return $this->belongsTo(Task::class);
    }

    public function sendMail() {

        $notification = $this;

        $user = User::find($notification->user->id);

        Mail::send('mail.notification-mail', ['notification' => $notification], function ($m) use ($user) {
            $m->from('todo@app.com', 'ToDo App');

            $m->to($user->email, $user->name)->subject('New notification!');
        });

    }

}
