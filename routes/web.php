<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoomStatusController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\TypeController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\TypeImageController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\RoleMiddleware;



Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');

//admin..............................................

//login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/password/change', [AuthController::class, 'showChangePasswordForm'])->name('password.change');
Route::post('/password/change', [AuthController::class, 'changePassword'])->name('password.update');

//dashboard
Route::get('admin/dashboard',
 [DashboardController::class, 'index'])
 ->name('admin.dashboard');
Route::get('admin/dashboard_employee',
 [DashboardController::class, 'index_employee'])
 ->name('admin.dashboard_employee');

//roomstatus
Route::middleware(['auth', 'role:admin'])->group(function () {
Route::get('/admin/roomstatus', 
[RoomStatusController::class, 'index'])
    ->name('admin.roomstatus.index');

Route::get('/admin/roomstatus/create', 
[RoomStatusController::class, 'create'])
    ->name('admin.roomstatus.create');

Route::post('admin/roomstatus/save',
[RoomStatusController::class, 'save'])
->name('admin.roomstatus.save');

Route::get('/admin/roomstatus/edit', 
[RoomStatusController::class, 'edit'])
    ->name('admin.roomstatus.edit');

Route::post('admin/roomstatus/update',
[RoomStatusController::class, 'update'])
->name('admin.roomstatus.update');

Route::post('/admin/roomstatus/delete', 
[RoomStatusController::class, 'destroy'])
->name('admin.roomstatus.delete');

Route::get('admin/roomstatus/export', 
[RoomStatusController::class, 'exportExcel'])
->name('admin.roomstatus.export');

//facility
Route::get('/admin/facility', 
[FacilityController::class, 'index'])
    ->name('admin.facility.index');

Route::get('/admin/facility/create', 
[FacilityController::class, 'create'])
    ->name('admin.facility.create');

Route::post('admin/facility/save',
[FacilityController::class, 'save'])
->name('admin.facility.save');

Route::get('/admin/facility/edit', 
[FacilityController::class, 'edit'])
    ->name('admin.facility.edit');

Route::post('admin/facility/update',
[FacilityController::class, 'update'])
->name('admin.facility.update');

Route::post('/admin/facility/delete', 
[FacilityController::class, 'destroy'])
->name('admin.facility.delete');

Route::get('admin/facility/export', 
[FacilityController::class, 'exportExcel'])
->name('admin.facility.export');

//type
Route::get('/admin/type', 
[TypeController::class, 'index'])
    ->name('admin.type.index');

Route::get('/admin/type/create', 
[TypeController::class, 'create'])
    ->name('admin.type.create');

Route::post('admin/type/save',
[TypeController::class, 'save'])
->name('admin.type.save');

Route::get('/admin/type/edit', 
[TypeController::class, 'edit'])
    ->name('admin.type.edit');

Route::post('admin/type/update',
[TypeController::class, 'update'])
->name('admin.type.update');

Route::post('/admin/type/delete', 
[TypeController::class, 'destroy'])
->name('admin.type.delete');

Route::get('admin/type/export', 
[TypeController::class, 'exportExcel'])
->name('admin.type.export');


//TypeImages
Route::get('/admin/typeimage', 
[TypeImageController::class, 'index'])
    ->name('admin.typeimage.index');

Route::get('/admin/typeimage/create', 
[TypeImageController::class, 'create'])
    ->name('admin.typeimage.create');

Route::post('admin/typeimage/save',
[TypeImageController::class, 'save'])
->name('admin.typeimage.save');

Route::get('/admin/typeimage/edit', 
[TypeImageController::class, 'edit'])
    ->name('admin.typeimage.edit');

Route::post('admin/typeimage/update',
[TypeImageController::class, 'update'])
->name('admin.typeimage.update');

Route::post('/admin/typeimage/delete', 
[TypeImageController::class, 'destroy'])
->name('admin.typeimage.delete');

Route::get('admin/typeimage/export', 
[TypeImageController::class, 'exportExcel'])
->name('admin.typeimage.export');

//employee
Route::get('/admin/employee', 
[EmployeeController::class, 'index'])
    ->name('admin.employee.index');

Route::get('/admin/employee/create', 
[EmployeeController::class, 'create'])
    ->name('admin.employee.create');

Route::post('admin/employee/save',
[EmployeeController::class, 'save'])
->name('admin.employee.save');

Route::get('/admin/employee/edit', 
[EmployeeController::class, 'edit'])
    ->name('admin.employee.edit');

Route::post('admin/employee/update',
[EmployeeController::class, 'update'])
->name('admin.employee.update');

Route::post('/admin/employee/delete', 
[EmployeeController::class, 'destroy'])
->name('admin.employee.delete');

Route::get('admin/employee/export', 
[EmployeeController::class, 'exportExcel'])
->name('admin.employee.export');


//account
Route::get('/admin/account', 
[AccountController::class, 'index'])
    ->name('admin.account.index');

Route::get('/admin/account/create', 
[AccountController::class, 'create'])
    ->name('admin.account.create');

Route::post('admin/account/save',
[AccountController::class, 'save'])
->name('admin.account.save');

Route::get('/admin/account/edit', 
[AccountController::class, 'edit'])
    ->name('admin.account.edit');

Route::post('admin/account/update',
[AccountController::class, 'update'])
->name('admin.account.update');

Route::post('/admin/account/delete', 
[AccountController::class, 'destroy'])
->name('admin.account.delete');

Route::get('admin/account/export', 
[AccountController::class, 'exportExcel'])
->name('admin.account.export');
});

Route::middleware(['auth', 'role:admin|employee'])->group(function () {
//customer
Route::get('/admin/customer', 
[CustomerController::class, 'index'])
    ->name('admin.customer.index');

Route::get('/admin/customer/create', 
[CustomerController::class, 'create'])
    ->name('admin.customer.create');

Route::post('admin/customer/save',
[CustomerController::class, 'save'])
->name('admin.customer.save');

Route::get('/admin/customer/edit', 
[CustomerController::class, 'edit'])
    ->name('admin.customer.edit');

Route::post('admin/customer/update',
[CustomerController::class, 'update'])
->name('admin.customer.update');

Route::post('/admin/customer/delete', 
[CustomerController::class, 'destroy'])
->name('admin.customer.delete');

Route::get('admin/customer/export', 
[CustomerController::class, 'exportExcel'])
->name('admin.customer.export');


//room
Route::get('/admin/room', 
[RoomController::class, 'index'])
    ->name('admin.room.index');

Route::get('/admin/room/create', 
[RoomController::class, 'create'])
    ->name('admin.room.create');

Route::post('admin/room/save',
[RoomController::class, 'save'])
->name('admin.room.save');

Route::get('/admin/room/edit', 
[RoomController::class, 'edit'])
    ->name('admin.room.edit');

Route::post('admin/room/update',
[RoomController::class, 'update'])
->name('admin.room.update');

Route::post('/admin/room/delete', 
[RoomController::class, 'destroy'])
->name('admin.room.delete');

Route::get('admin/room/export', 
[RoomController::class, 'exportExcel'])
->name('admin.room.export');



//reservation
Route::get('/admin/reservation', 
[ReservationController::class, 'index'])
    ->name('admin.reservation.index');

Route::get('/admin/reservation/create', 
[ReservationController::class, 'create'])
    ->name('admin.reservation.create');

Route::get('/admin/reservation/getAvailableRooms',
[ReservationController::class, 'getAvailableRooms'])
->name('admin.reservation.getAvailableRooms');

Route::post('admin/reservation/save',
[ReservationController::class, 'save'])
->name('admin.reservation.save');

Route::get('/admin/reservation/edit', 
[ReservationController::class, 'edit'])
    ->name('admin.reservation.edit');

Route::post('admin/reservation/update',
[ReservationController::class, 'update'])
->name('admin.reservation.update');

Route::post('/admin/reservation/delete', 
[ReservationController::class, 'destroy'])
->name('admin.reservation.delete');

Route::get('admin/reservation/export', 
[ReservationController::class, 'exportExcel'])
->name('admin.reservation.export');

//payment
Route::get('/admin/payment', 
[PaymentController::class, 'index'])
    ->name('admin.payment.index');

Route::get('/admin/payment/create', 
[PaymentController::class, 'create'])
    ->name('admin.payment.create');

Route::post('admin/payment/save',
[PaymentController::class, 'save'])
->name('admin.payment.save');

Route::get('/admin/payment/edit', 
[PaymentController::class, 'edit'])
    ->name('admin.payment.edit');

Route::post('admin/payment/update',
[PaymentController::class, 'update'])
->name('admin.payment.update');

Route::post('/admin/payment/delete', 
[PaymentController::class, 'destroy'])
->name('admin.payment.delete');

Route::get('admin/payment/export', 
[PaymentController::class, 'exportExcel'])
->name('admin.payment.export');
});

//client..............................................
// routes/web.php
require base_path('routes/customer.php');