<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function showMembershipExpiryNotification(Notification $notification)
    {
        return response()->json(json_decode($notification->data),200);
    }
}
