<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    // Tạo thông báo
    public function createNotification(Request $request)
    {
        $customerId = $request->customer_id;
        $message = $request->message;

        $notification = new Notification();
        $notification->customer_id = $customerId;
        $notification->message = $message;
        $notification->created_at = now();
        $notification->save();

        return response()->json(data: ['success' => true, 'data' => $notification], status: 201);
    }

    // Lấy danh sách thông báo
    public function getNotifications($customer_id)
    {
        $notifications = Notification::where('customer_id', $customer_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['success' => true, 'data' => $notifications], 200);
    }

    public function deleteNotification($notification_id)
    {
        try {
            Log::info('Delete notification with id: ' . $notification_id);
            $notification = Notification::find($notification_id);
            $notification->destroy($notification_id);

            return response()->json(['success' => true, 'message' => 'Thông báo đã được xóa.'], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting notification: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Không thể xóa thông báo.'], 500);
        }
    }

    
}