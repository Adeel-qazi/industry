<?php

use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserSubscriptionController;
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
        Route::get('profile/{userId}', [UserController::class, 'show']);
        Route::put('profile/{userId}', [UserController::class, 'update']);

        Route::get('status-update/{userId}',[UserController::class, 'statusUpdated']);
        Route::get('users',[UserController::class, 'fetchUser']);
        Route::post('add-user',[UserController::class, 'addUser']);
        Route::get('edit-user/{userId}',[UserController::class, 'editUser']);
        Route::put('update-user/{userId}',[UserController::class, 'updateUser']);
        Route::delete('delete-user/{userId}',[UserController::class, 'destroyUser']);

        Route::resource('organizations',OrganizationController::class);

        Route::post('organizations-import', [OrganizationController::class, 'import']);
        Route::get('organizations-export', [OrganizationController::class, 'export']);

        //subscription
        Route::resource('subscriptions',SubscriptionController::class);
        Route::get('status/{subscriptionId}',[SubscriptionController::class, 'statusUpdated']);




    });


        Route::group(['middleware'=>'user'],function(){

            Route::get('profile/{userId}',[UserController::class,'show']);
            Route::put('profile/{userId}',[UserController::class,'update']);

            Route::middleware('is_subscribe')->group(function(){
                Route::get('news-feed',[UserController::class,'allProfile']);//1
            });

    
            //user-subscribe
            Route::middleware('show_package')->group(function(){
                Route::get('/package',[UserSubscriptionController::class,'index'])->name('user.package');//2
            });

            
            Route::get('show-subscriptions',[SubscriptionController::class,'getAllSubscriptions']);


            Route::get('profiles/follow/{profile}',[OrganizationController::class,'followProfile']);
            Route::get('followed-profiles',[OrganizationController::class,'getAllProfiles']);



        Route::post('stripe',[UserSubscriptionController::class,'storePackage']);
            
        });





});
