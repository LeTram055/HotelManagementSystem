<?php
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Customer\TypeController;


Route::get('client/types', [TypeController::class, 'index']);
Route::get('client/types/{id}', [TypeController::class, 'show']);