<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RideController;


/* 1. */ Route::post('/auth/user/signup', [AuthController::class, 'userSignup']);
/* 2. */ Route::post('/auth/buddy/signup', [AuthController::class, 'buddySignup']);
/* 3. */ Route::post('/auth/user/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
/* 4. */ Route::post('/auth/logout', [AuthController::class, 'logout']);


/* 5. */ Route::get('/profile', [ProfileController::class, 'getProfile']);
 /* 6. */ Route::post('/profile/update', [ProfileController::class, 'updateProfile']);

    // Route::post('/rides/request', [RideController::class, 'createRideRequest']);
    // Route::get('/user/my-rides', [RideController::class, 'userRides']);

    // Route::get('/buddy/rides', [RideController::class, 'availableRides']);
    // Route::post('/rides/accept', [RideController::class, 'acceptRide']);
    // Route::post('/rides/ignore', [RideController::class, 'ignoreRide']);
    // Route::get('/buddy/my-rides', [RideController::class, 'buddyRides']);

});


Route::post('/auth/user/signup', [AuthController::class, 'userSignup']);
Route::post('/auth/buddy/signup', [AuthController::class, 'buddySignup']);
Route::post('/auth/user/login', [AuthController::class, 'login']);

