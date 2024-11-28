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

        // Lấy tất cả các phòng
        $rooms = Rooms::with('type', 'status')->get();

        // Lấy danh sách các đơn đặt phòng trong khoảng thời gian
        $reservations = Reservations::where(function ($query) use ($checkin, $checkout) {
            if ($checkin === $checkout) {
            // Kiểm tra xem start_date có nằm trong khoảng checkin và checkout của đơn đặt phòng không
                $query->where(function($q) use ($checkin) {
                    $q->where('reservation_checkin', '<=', $checkin)
                    ->where('reservation_checkout', '>=', $checkin);
                });
            } else {
                // Nếu ngày bắt đầu và ngày kết thúc khác nhau, sử dụng whereBetween như bình thường
                $query->whereBetween('reservation_checkin', [$checkin, $checkout])
                    ->orWhereBetween('reservation_checkout', [$checkin, $checkout]);
            }
        })->get();

        // Lọc các phòng dựa trên trạng thái và đơn đặt phòng
        $availableRooms = $rooms->filter(function ($room) use ($reservations) {
            $isAvailable = true;

            foreach ($reservations as $reservation) {
                if ($reservation->rooms->contains('room_id', $room->room_id) && $reservation->reservation_status !== 'Đã trả phòng' && $reservation->reservation_status !== 'Đã hủy') {
                    
                    $isAvailable = false;
                    break;
                    
                    
                }
            }

            if ($room->status->status_name === 'Đang sửa') {
                $isAvailable = false;
            }

            return $isAvailable;
        });

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
            'checkout' => 'required|date_format:Y-m-d|after_or_equal:checkin',
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

    public function getCustomerReservations($customerId)
    {
        $reservations = Reservations::with(['rooms:room_id'])
            ->where('customer_id', $customerId)
            ->orderBy('reservation_date', 'desc')
            ->get();

        return response()->json($reservations);
    }

    public function getRoomsDetails(Request $request)
    {
        $roomIds = $request->input('room_ids');
        $rooms = Rooms::whereIn('room_id', $roomIds)->get();

        return response()->json($rooms);
    }

    public function cancelReservation($id)
{
    $reservation = Reservations::findOrFail($id);

    // Kiểm tra trạng thái hiện tại của đơn đặt
    if ($reservation->reservation_status !== 'Chờ xác nhận') {
        return response()->json(['message' => 'Không thể hủy đơn đã xử lý'], 400);
    }

    // Cập nhật trạng thái đơn đặt thành "Đã hủy"
    $reservation->reservation_status = 'Đã hủy';
    $reservation->save();

    // Cập nhật trạng thái các phòng liên quan
    $roomReservations = RoomReservation::where('reservation_id', $reservation->reservation_id)->get();

    foreach ($roomReservations as $roomReservation) {
        $room = Rooms::find($roomReservation->room_id);

        // Kiểm tra các đơn khác có sử dụng phòng này với trạng thái "Đã nhận phòng" hoặc "Đã xác nhận"
        $otherReservations = RoomReservation::where('room_id', $room->room_id)
            ->where('reservation_id', '!=', $reservation->reservation_id)
            ->whereHas('reservation', function ($query) {
                $query->whereIn('reservation_status', ['Đã nhận phòng', 'Đã xác nhận']);
            })
            ->exists();

        if (!$otherReservations) {
            // Nếu không còn đơn nào liên quan, cập nhật trạng thái phòng về trống
            $room->update(['status_id' => 1]); // 1: Trạng thái phòng trống
        }
    }

    return response()->json(['message' => 'Hủy đơn đặt phòng thành công'], 200);
}


}