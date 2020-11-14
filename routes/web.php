<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Backoffice\BookingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect(route('login'));
});

Auth::routes();

Route::group(['prefix'=> 'backoffice','middleware' => 'auth'], function() {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{id}/cancel', [BookingController::class, 'set_cancel'])->name('bookings.set_cancel');
    Route::get('/bookings/{id}/selesaikan', [BookingController::class, 'set_done'])->name('bookings.set_done');
    Route::get('/bookings/{id}/send-notification', [BookingController::class, 'send_notif'])->name('bookings.send_notif');

    Route::get('/notif/compose', function () {
        return view('backoffice.notifications.send');
    })->name('notif_send');;

    Route::post('/notif/send', [BookingController::class, 'send_notif_message'])->name('message_notif.send');
});
