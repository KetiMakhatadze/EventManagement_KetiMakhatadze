<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\BookingController;

// მთავარი გვერდი
Route::get('/', [EventController::class, 'home'])->name('home');

// ღონისძიებები
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

// ავტორიზაცია
Route::get('/login', function() { return view('auth.login'); })->name('login');
Route::get('/register', function() { return view('auth.register'); })->name('register');
Route::post('/logout', function() { 
    auth()->logout(); 
    return redirect('/'); 
})->name('logout');

// დაჯავშნები (ავტორიზებული)
Route::middleware('auth')->group(function () {
    Route::get('/my-bookings', [BookingController::class, 'index'])->name('bookings.index');
});

// ადმინი (ავტორიზებული)
Route::middleware(['auth'])->group(function () {
    Route::get('/admin', function() { return view('admin.dashboard'); })->name('admin.dashboard');
});