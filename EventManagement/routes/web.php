<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Models\Event;

// მთავარი გვერდი
Route::get('/', function () {
    $featured_events = Event::where('status', 'published')
        ->latest('start_date')
        ->take(6)
        ->get();
    return view('welcome', compact('featured_events'));
})->name('home');

// Authentication Routes
Auth::routes();

// Public Event Routes
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

// Booking Routes (Auth Required)
Route::middleware('auth')->group(function () {
    Route::get('/events/{event}/book', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('bookings.my');
});

// Participant Check-in (Public with unique ID)
Route::get('/checkin/{id}', [ParticipantController::class, 'checkIn'])->name('participants.checkin');

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('events', AdminEventController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('bookings', AdminBookingController::class)->only(['index', 'show', 'destroy']);
    
    Route::get('/participants', [ParticipantController::class, 'list'])->name('participants.index');
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
