<?php

namespace App\Services;

use Illuminate\Support\Facades\Notification;
use App\Notifications\ActivityNotification;

class NotificationService
{
    /**
     * Send a notification to one or multiple users.
     *
     * @param  \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|array  $users
     * @param  array  $details
     * @return void
     */
    public function sendNotification($users, array $details)
    {
        $notification = new ActivityNotification($details);
         Notification::send($users, $notification);

         //return $details;
    }
}