<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoomStatusController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\TypeController;

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