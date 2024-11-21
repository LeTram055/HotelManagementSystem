<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Types;
use App\Models\Rooms;
use App\Models\Reservations;
use App\Models\RoomReservation;

class ReservationController extends Controller
{

    public function getAvailableRoomTypes(Request $request)
{
    $request->validate([
        'checkin' => 'required|date_format:Y-m-d',
        'checkout' => 'required|date_format:Y-m-d',
    ]);

    $checkin = $request->input('checkin');
    $checkout = $request->input('checkout');

    $availableRooms = Rooms::with('type') // Eager load loại phòng
        ->where(function ($query) use ($checkin, $checkout) {
            $query->where('status_id', 1) // Phòng trống
                ->orWhere(function ($subQuery) use ($checkin, $checkout) {
                    $subQuery->whereDoesntHave('reservations', function ($query) use ($checkin, $checkout) {
                        $query->where(function ($query) use ($checkin, $checkout) {
                            $query->where('reservation_checkin', '<=', $checkout)
                                ->where('reservation_checkout', '>=', $checkin);
                        });
                    });
                });
        })
        ->where('status_id', '!=', 4)
        ->get();

    // Nhóm danh sách phòng trống theo loại phòng
    $groupedRooms = $availableRooms->groupBy('type_id')->map(function ($rooms, $typeId) {
        $type = $rooms->first()->type; // Lấy thông tin loại phòng từ phòng đầu tiên
        return [
            'type_id' => $type->type_id,
            'type_name' => $type->type_name,
            'type_price' => $type->type_price,
            'list_available_rooms' => $rooms->map(function ($room) {
                return [
                    'room_id' => $room->room_id,
                    'room_name' => $room->room_name,
                ];
            }),
        ];
    })->values(); // Chuyển thành array tuần tự (không giữ key type_id)

    return response()->json($groupedRooms);
}

public function createReservation(Request $request)
{
    $request->validate([
        'customer_id' => 'required|exists:customers,customer_id',
        'checkin' => 'required|date_format:Y-m-d',
        'checkout' => 'required|date_format:Y-m-d|after:checkin',
        'room_ids' => 'required|array|min:1',
        'room_ids.*' => 'exists:rooms,room_id',
    ]);

    $checkin = $request->input('checkin');
    $checkout = $request->input('checkout');
    $customerId = $request->input('customer_id');
    $roomIds = $request->input('room_ids');


    try {
    // Lưu thông tin đặt phòng
        $reservation = new Reservations();
        $reservation->customer_id = $customerId;
        $reservation->reservation_date = now();
        $reservation->reservation_checkin = $checkin;
        $reservation->reservation_checkout = $checkout;
        $reservation->reservation_status = 'Chờ xác nhận';
        $reservation->save();
        
        
        // Lưu thông tin các phòng đã đặt
        foreach ($roomIds as $roomId) {
            $roomReservation = new RoomReservation();
            $roomReservation->reservation_id = $reservation->reservation_id;
            $roomReservation->room_id = (int) $roomId;
            $roomReservation->save();
        }

    
        // // Tạo một giao dịch đặt phòng
        // $reservation = Reservations::create([
        //     'customer_id' => $customerId,
        //     'reservation_date' => now(),
        //     'reservation_checkin' => $checkin,
        //     'reservation_checkout' => $checkout,
        //     'reservation_status' => 'pending', // Trạng thái mặc định
        // ]);

        // // Gán phòng cho đặt phòng
        // foreach ($roomIds as $roomId) {
        //     $reservation->rooms()->attach($roomId);
        // }

        return response()->json([
            'message' => 'Đặt phòng thành công!',
            'reservation_id' => $reservation->reservation_id,
        ], 201);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Có lỗi xảy ra khi đặt phòng!',
            'error' => $e->getMessage(),
        ], 500);
    }
}



}