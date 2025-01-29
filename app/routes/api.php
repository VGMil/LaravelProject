<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\RoomsController;

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
