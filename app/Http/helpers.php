<?php

use App\Models\Notification;

if( !function_exists('get_notification_message') )  {
    function get_notification_message($type, $user = false, $task = false, $sender = false) {

        switch($type) {
            case 0:
                return __('notifications.invitation', ['user' => $sender, 'task' => $task]);
            case 1:
                return __('notifications.kicked', ['task' => $task]);
            case 2:
                return __('notifications.kickedOther', ['user' => $sender, 'task' => $task]);
            case 3:
                return __('notifications.join', ['user' => $sender, 'task' => $task]);
            case 4:
                return __('notifications.leave', ['user' => $sender, 'task' => $task]);
            case 5:
                return __('notifications.deleted', ['user' => $sender, 'task' => $task]);
            case 6:
                return __('notifications.completed', ['user' => $sender, 'task' => $task]);
            case 7:
                return __('notifications.updated', ['user' => $sender, 'task' => $task]);
            case 8:
                return __('notifications.comment', ['user' => $sender, 'task' => $task]);
            default:
                return '';
        }

    }
}

if( !function_exists('send_notification') ) {
    function send_notification($type, $task, $to, $from, $external = false) {

        $notification = new Notification();

        $notification->type = $type;

        $notification->sender_id = $from;

        $notification->task_id = $task;

        $notification->user_id = $to;

        if($external) {
            $notification->external_id = $external;
        }

        $notification->save();

        $notification->sendMail();

    }
}

if( !function_exists('broadcast_notification') ) {
    function broadcast_notification($user_group, $type, $task, $from, $external = false, $exception = false) {

        foreach($user_group as $user) {

            if($exception !== false && $exception == $user->id) {
                continue;
            }

            send_notification($type, $task, $user->id, $from, $external);

        }

    }
}

if( !function_exists('get_status_icon') ) {
    function get_status_icon($status) {

        switch($status) {
            case 'success':
                return '<i class="material-icons text-success">check</i>';
            case 'failure':
                return '<i class="material-icons text-failure">close</i>';
            default:
                return '';
        }

    }
}

if( !function_exists('get_task_count_text') ) {
    function get_task_count_text($count) {

        if($count == 1) {
            return __('tasks.task');
        }

        if($count < 5) {
            return __('tasks.tasks');
        }

        return __('tasks.tasksMore');

    }
}