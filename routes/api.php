<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HotelController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API rosutes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// route for fetch all active hotels data
Route::get('hotels', [HotelController::class,'getAllHotelData']);

// route for fetch the active hotel data based on hotel id
Route::get('hotel/{hotel_id}', [HotelController::class,'getHotelDataById']);

// route for store new hotel review
Route::post('save-hotel-review', [HotelController::class,'storeHotelReviewData']);

// route for store new hotel review
Route::put('update-hotel-review/{review_id}', [HotelController::class,'updateHotelReviewData']);

// Delete review for hotel
Route::delete('review/{review_id}', [HotelController::class,'deleteHotelReview']);