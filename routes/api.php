<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\HospitalController;

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



Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);


// list doctor
Route::get('/doctor/list', [DoctorController::class, 'doctors']);
// detail doctor
Route::get('/doctor/{id}', [DoctorController::class, 'doctor']);

// content 
Route::get('/content', [ContentController::class, 'list']);
Route::get('/content/search', [ContentController::class, 'search']);

// hospital
Route::get('/department/{depid}/hospitals', [HospitalController::class, 'hospitals']);
Route::get('/department/{depid}/hospital/{id}/', [HospitalController::class, 'detail']);

Route::group(['middleware' => 'auth:api'], function(){

    Route::post('user', [UserController::class, 'detail']);

    // booking
    Route::get('bookings', [BookingController::class, 'patientBookings']);
    Route::get('booking/{id}', [BookingController::class, 'booking']);
    Route::post('booking/create', [BookingController::class, 'makeBooking']);
});
