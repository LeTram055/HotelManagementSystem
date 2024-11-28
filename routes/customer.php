<?php
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Customer\TypeController;
    use App\Http\Controllers\Customer\CustomerController;
    use App\Http\Controllers\Customer\ReservationController;
    use App\Http\Controllers\Customer\NotificationController;
    use App\Http\Controllers\Customer\PaymentController;
    
//loai phong
Route::get('customer/types', [TypeController::class, 'index']);
Route::get('customer/types/{id}', [TypeController::class, 'show']);

//xac thuc
Route::post('customer/register', [CustomerController::class, 'register']);
Route::post('customer/login', [CustomerController::class, 'login']);
Route::get('customer/getuserbyusername/{username}', [CustomerController::class, 'getUserByUsername']);
Route::post('customer/changepassword', [CustomerController::class, 'changePassword']);

//dat phong
Route::get('customer/available-room-types', [ReservationController::class, 'getAvailableRoomTypes']);
Route::post('customer/reservations', [ReservationController::class, 'createReservation']);
Route::get('customer/reservations/{customer_id}', [ReservationController::class, 'getCustomerReservations']);
Route::delete('customer/reservations/{id}/cancel', [ReservationController::class, 'cancelReservation']);
Route::get('customer/rooms', [ReservationController::class, 'getRoomsDetails']);

//thong bao
Route::post('customer/notifications', [NotificationController::class, 'createNotification']);
Route::get('customer/notifications/{customer_id}', [NotificationController::class, 'getNotifications']);
Route::delete('customer/notifications/{notification_id}', [NotificationController::class, 'deleteNotification']);


//
Route::get('customer/payment/invoice/{id}', 
[PaymentController::class, 'exportInvoice'])
->name('customer.payment.invoice');