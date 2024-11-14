<?php
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Customer\TypeController;


Route::get('customer/types', [TypeController::class, 'index']);
Route::get('customer/types/{id}', [TypeController::class, 'show']);