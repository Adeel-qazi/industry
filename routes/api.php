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
        Route::get('/dashboard', [UserController::class, 'dashboard']);
        Route::get('profile/{userId}', [UserController::class, 'show']);
        Route::put('profile/{userId}', [UserController::class, 'update']);

        Route::get('approved/{userId}',[UserController::class, 'approved']);
        Route::get('disapprove/{userId}',[UserController::class, 'disApproved']);
        Route::get('users',[UserController::class, 'fetchUser']);

        Route::resource('organizations',OrganizationController::class);

        // Route::get('/products-view',[OrganizationController::class, 'importView'])->name('import-view');
        Route::post('organizations-import', [OrganizationController::class, 'import']);
        Route::get('organizations-export', [OrganizationController::class, 'export']);

        //subscription
        Route::resource('subscriptions',SubscriptionController::class);




    });


        Route::group(['middleware'=>'user'],function(){
            // Route::get('/dashboard',[UserController::class,'dashboard']);
            Route::get('profile/{userId}',[UserController::class,'show']);
            Route::put('profile/{userId}',[UserController::class,'update']);

            Route::middleware('is_subscribe')->group(function(){
                Route::get('/news-feed',[UserController::class,'profile']);//1
            });
    
            //user-subscribe
            Route::middleware('show_package')->group(function(){
                Route::get('/package',[UserSubscriptionController::class,'index'])->name('user.package');//2
            });


              //checkout user-subscribe
        // Route::get('/checkout/{subscription}',[UserSubscriptionController::class,'createPackage'])->name('user.buy');//3
        Route::post('/success',[UserSubscriptionController::class,'storePackage'])->name('checkout.success');//4
        Route::get('/cancel',[UserSubscriptionController::class,'cancelPackage'])->name('checkout.cancel');//5
            
        });




});
