<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BookingListController;
use App\Http\Controllers\API\BookingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/auth/login',[AuthController::class,'authenticate'])->name('authenticate');

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/auth/refresh', [AuthController::class, 'refresh'])->name('refresh');
    Route::get('/auth/me', [AuthController::class, 'me'])->name('me');

    Route::get('/me', function (Request $request) {
        return $request->user();
    })->middleware('access:dasboard-read');

    Route::controller(BookingListController::class)->name('BookingList.')->group(function(){
        Route::get('/bookinglist/list', 'list')->name('list');
        Route::get('/bookinglist/row/{id}', 'byID')->name('byID');
        Route::get('/bookinglist/by-date/list', 'byDate')->name('byDate');
        // Route::post('/bookinglist/create', 'create')->name('create');
        Route::post('/bookinglist/create-by-month', 'createByMonth')->name('createByMonth');
        Route::put('/bookinglist/update/{id}', 'update')->name('update');
        Route::delete('/bookinglist/remove/{id}', 'remove')->name('remove');
    });
});

Route::controller(BookingController::class)->name('Booking.')->group(function () {
    Route::get('/booking/list-date', 'bookedCheckByMonth')->name('bookedCheckByMonth');
    Route::get('/booking/list-time', 'bookedListTime')->name('bookedListTime');
    Route::get('/booking/check-by-date', 'checkByDate')->name('checkByDate');
    Route::post('/booking/booked', 'booked')->name('booked');
    Route::get('/booking/check/{token}', 'bookedCheckbyToken')->name('bookedCheckbyToken');
    Route::post('/booking/confirm', 'bookConfirm')->name('bookConfirm');
});
