<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function createNotification($customerId, $message)
    {
        try {
            $notification = new Notification();
            $notification->customer_id = $customerId;
            $notification->message = $message;
            $notification->created_at = now();
            $notification->save();

            return $notification;
        } catch (\Exception $e) {
            return null;
        }
    }
}