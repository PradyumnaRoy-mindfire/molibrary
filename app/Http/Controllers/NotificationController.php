<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use App\Models\Notification;
use App\Models\User;
use App\Notifications\PendingFineNotification;
use Illuminate\Http\Request;
use Svg\CssLength;

class NotificationController extends Controller
{
    public function showMembershipExpiryNotification(Notification $notification)
    {
        return response()->json(json_decode($notification->data), 200);
    }


    public function markAsRead($notificationId)
    {
        $notification = auth()->user()->notifications()->where('id', $notificationId)->first();

        if ($notification) {
            $notification->markAsRead();
        }

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);
        return response()->json(['success' => true]);
    }

    public function sendingFineNotification(Fine $fine, User $user)
    {
        $user->notify(new PendingFineNotification($fine));
        return response()->json(['success' => true]);
    }
}
