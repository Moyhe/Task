<?php

use App\Http\Controllers\API\Admin\AdminAuthController;
use App\Http\Controllers\API\Admin\AdminController;
use App\Http\Controllers\API\Auth\AuthUserController;
use App\Http\Controllers\API\Post\CommentController;
use App\Http\Controllers\API\Post\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// admin
Route::post('admin/login', [AdminAuthController::class, 'login'])->name('admin.login');

Route::middleware(['auth:jwt', 'role:admin'])->prefix('admin')->group(function () {

    Route::apiResource('posts', AdminController::class);
    Route::post('create', [AdminAuthController::class, 'register'])->name('admin.create');
});


// users

Route::middleware(['auth:jwt', 'role:author'])->group(function () {

    Route::apiResource('posts', PostController::class);
    Route::post('posts/{id}/comments', [CommentController::class, 'store'])->name('comment.store');
});


Route::middleware('guest')->group(function () {

    Route::post('register', [AuthUserController::class, 'register'])->name('api.register');
    Route::post('login', [AuthUserController::class, 'login'])->name('api.login');
});
