<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;

Route::get('/customer',[CustomerController::class,'index' ]);
Route::get('customer/{id}',[CustomerController::class,'show' ]);
Route::post('/customer',[CustomerController::class,'store' ]);
Route::put('/customer/{id}',[CustomerController::class,'update' ]);
Route::patch('/customer/{id}',[CustomerController::class,'updatePartial' ]);
Route::delete('/customer/{id}',[CustomerController::class,'destroy' ]);

