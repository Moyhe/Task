<?php

use App\Http\Controllers\API\Auth\AuthUserController;
use App\Http\Controllers\API\Post\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:jwt');



Route::middleware(['auth:jwt', 'role:author'])->group(function () {

    Route::apiResource('posts', PostController::class);
});

Route::middleware('guest')->group(function () {

    Route::post('/user/register', [AuthUserController::class, 'register'])->name('api.register');
    Route::post('/user/login', [AuthUserController::class, 'login'])->name('api.login');
});
