<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function () {
    //Route Login And Register
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    // Route Logout and Me
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    //Route Hanya Untuk Admin
    Route::prefix('admin')->middleware('admin')->group(function() {
        //Route Resource Category(Admin)
        Route::resource('category', CategoryController::class);
        // Route Resource Book (Admin)
        Route::resource('books', BooksController::class);
    });

    
});