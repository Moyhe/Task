<?php

use App\Http\Controllers\API\Admin\AdminController;
use App\Http\Controllers\API\Auth\AuthUserController;
use App\Http\Controllers\API\Post\CommentController;
use App\Http\Controllers\API\Post\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware(['auth:jwt', 'role:admin'])->prefix('admin')->group(function () {

    Route::apiResource('posts', AdminController::class);
});

Route::middleware(['auth:jwt', 'role:author'])->group(function () {

    Route::apiResource('posts', PostController::class);
    Route::post('posts/{id}/comments', [CommentController::class, 'store']);
});


Route::middleware('guest')->group(function () {

    Route::post('/user/register', [AuthUserController::class, 'register'])->name('api.register');
    Route::post('/user/login', [AuthUserController::class, 'login'])->name('api.login');
});
