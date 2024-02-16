<?php

use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);


Route::group(['middleware' => 'auth:api'], function () {


    Route::group(['middleware' => 'admin'], function () {
        Route::get('/dashboard', [UserController::class, 'dashboard']);
        Route::get('profile/{userId}', [UserController::class, 'show']);
        Route::put('profile/{userId}', [UserController::class, 'update']);

        Route::get('approved/{userId}',[UserController::class, 'approved']);
        Route::get('disapprove/{userId}',[UserController::class, 'disApproved']);

        Route::resource('organizations',OrganizationController::class);



    });


        Route::group(['middleware'=>'user'],function(){
            Route::get('/dashboard',[UserController::class,'dashboard']);
            Route::get('profile/{userId}',[UserController::class,'show']);
            Route::put('profile/{userId}',[UserController::class,'update']);
            
             //appointments
            // Route::post('appointments/store',[AppointmentController::class,'store']);
            // Route::put('appointments/booked/{slotId}',[AppointmentController::class,'bookedSlot']);
            // Route::get('appointments/edit/{event}',[AppointmentController::class,'edit']);
        });




});
