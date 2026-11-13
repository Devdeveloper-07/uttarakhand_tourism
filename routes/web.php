<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\Admin\HotelController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\RoomTypeController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\BookingPaymentController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [HomeController::class,'index'])->name('home');

Route::get('/hotels',[HomeController::class,'hotels'])->name('hotels');

Route::get('/hotel/{id}', [HomeController::class,'hotelDetails'])
    ->name('hotel.details');
Route::get('/hotel/{hotel}/availability', [HomeController::class, 'hotelAvailability'])
    ->name('hotel.availability');

Route::get('/packages',[HomeController::class,'packages'])->name('packages');

Route::get('/package/{id}', [HomeController::class,'packageDetails'])
    ->name('package.details');

Route::post('/hotel/{hotel}/book', [BookingPaymentController::class, 'startHotel'])
    ->name('hotel.booking.start');
Route::post('/package/{package}/book', [BookingPaymentController::class, 'startPackage'])
    ->name('package.booking.start');
Route::post('/payments/{payment}/verify', [BookingPaymentController::class, 'verify'])
    ->name('payments.verify');
Route::post('/payments/{payment}/failed', [BookingPaymentController::class, 'paymentFailed'])
    ->name('payments.failed');
Route::get('/payments/{payment}/invoice', [BookingPaymentController::class, 'invoice'])
    ->middleware('signed')
    ->name('payments.invoice');

Route::get('/gallery',[HomeController::class,'gallery'])->name('gallery');

Route::get('/contact',[HomeController::class,'contact'])->name('contact');
Route::get('/about', [HomeController::class, 'about'])->name('about');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {

        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('user.dashboard');

    })->name('dashboard');

});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
        Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
        Route::get('/payments/{payment}/invoice', [BookingPaymentController::class, 'invoice'])->name('payments.invoice');
        Route::get('/gst-settings', [\App\Http\Controllers\Admin\GstSettingsController::class, 'index'])->name('gst-settings.index');
        Route::put('/gst-settings/hotel-slabs', [\App\Http\Controllers\Admin\GstSettingsController::class, 'updateHotelSlabs'])->name('gst-settings.hotel-slabs.update');

        Route::resource('hotels', HotelController::class);
        Route::resource('rooms', RoomController::class);
        Route::resource('room-types', RoomTypeController::class);
        Route::resource('packages', PackageController::class);
        Route::resource('galleries', GalleryController::class);
        Route::resource('testimonials', TestimonialController::class);
        Route::resource('users', UserController::class);

    });

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {

      Route::get('/dashboard',[DashboardController::class,'dashboard'])->name('dashboard');

        Route::get('/bookings',[DashboardController::class,'bookings'])->name('bookings');
        Route::get('/bookings/{id}',[DashboardController::class,'bookingShow'])->name('bookings.show');

        Route::get('/hotels',[DashboardController::class,'hotels'])->name('hotels');
        Route::get('/hotels/{id}',[DashboardController::class,'hotelShow'])->name('hotels.show');

        Route::get('/packages',[DashboardController::class,'packages'])->name('packages');
        Route::get('/packages/{id}',[DashboardController::class,'packageShow'])->name('packages.show');

        Route::get('/profile',[DashboardController::class,'profile'])->name('profile');
        Route::post('/profile',[DashboardController::class,'updateProfile'])->name('profile.update');

    });

/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});

require __DIR__.'/auth.php';
