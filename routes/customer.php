<?php
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Customer\TypeController;
    use App\Http\Controllers\Customer\CustomerController;

//loai phong
Route::get('customer/types', [TypeController::class, 'index']);
Route::get('customer/types/{id}', [TypeController::class, 'show']);

//xac thuc
Route::post('customer/register', [CustomerController::class, 'register']);
Route::post('customer/login', [CustomerController::class, 'login']);
Route::get('customer/getuserbyusername/{username}', [CustomerController::class, 'getUserByUsername']);