<?php
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Customer\TypeController;
    use App\Http\Controllers\Customer\CustomerController;
    use App\Http\Controllers\Customer\ReservationController;

//loai phong
Route::get('customer/types', [TypeController::class, 'index']);
Route::get('customer/types/{id}', [TypeController::class, 'show']);

//xac thuc
Route::post('customer/register', [CustomerController::class, 'register']);
Route::post('customer/login', [CustomerController::class, 'login']);
Route::get('customer/getuserbyusername/{username}', [CustomerController::class, 'getUserByUsername']);

//dat phong
Route::get('customer/available-room-types', [ReservationController::class, 'getAvailableRoomTypes']);
Route::post('customer/reservations', [ReservationController::class, 'createReservation']);