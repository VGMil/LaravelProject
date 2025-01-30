<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\RoomsController;
use App\Http\Controllers\BookingsController;

Route::get('/customers',[CustomerController::class,'index' ]);
Route::get('customer/{id}',[CustomerController::class,'show' ]);
Route::post('/customer',[CustomerController::class,'store' ]);
Route::put('/customer/{id}',[CustomerController::class,'update' ]);
Route::patch('/customer/{id}',[CustomerController::class,'updatePartial' ]);
Route::delete('/customer/{id}',[CustomerController::class,'destroy' ]);

Route::get('/rooms',[RoomsController::class,'index' ]);
Route::get('/rooms/{id}',[RoomsController::class,'show' ]);
Route::post('/rooms',[RoomsController::class,'store' ]);
Route::patch('rooms/{id}',[RoomsController::class,'update' ]);
Route::delete('/rooms/{id}',[RoomsController::class,'destroy' ]);

Route::get('/bookings', [BookingsController::class,'index' ]);
Route::get('/bookings/{id}', [BookingsController::class,'show' ]);
Route::post('/bookings', [BookingsController::class,'store' ]);
Route::patch('/bookings/{id}', [BookingsController::class,'update' ]);
Route::delete('/bookings/{id}', [BookingsController::class,'destroy' ]);


