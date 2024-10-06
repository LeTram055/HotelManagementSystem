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


Route::get('/', function () {
    return view('welcome');
});
//roomstatus
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

//reservation
Route::get('/admin/reservation', 
[ReservationController::class, 'index'])
    ->name('admin.reservation.index');

Route::get('/admin/reservation/create', 
[ReservationController::class, 'create'])
    ->name('admin.reservation.create');

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