<?php

namespace App\Traits;

use App\Models\Notification;
use App\Models\User;

trait NotificationTrait 
{
    public function sendNotification(
        User $user,
        string $type,
        string $title,
        string $message,
        ?string $link = null
    ) {
        return Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'link' => $link,
        ]);
    }

    public function notifyAdmins($type, $title, $message, $link = null) 
    {
        $admins = User::where('role', 'admin')->get();
        
        foreach ($admins as $admin) {
            $this->sendNotification($admin, $type, $title, $message, $link);
        }
    }

    public function notifyStaff($type, $title, $message, $link = null) 
    {
        $staff = User::where('role', 'staff')->get();
        
        foreach ($staff as $staffMember) {
            $this->sendNotification($staffMember, $type, $title, $message, $link);
        }
    }
}
