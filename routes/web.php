<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:register');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Admin Routes
Route::middleware(['auth', 'role:superadmin|admin-kc'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('buses', \App\Http\Controllers\BusController::class);
    Route::get('/registrations', [\App\Http\Controllers\BusController::class, 'registrations'])->name('registrations.index');
    Route::get('/registrations/{registration}', [\App\Http\Controllers\BusController::class, 'registrationShow'])->name('registrations.show');
    Route::post('/registrations/{registration}/verify', [\App\Http\Controllers\BusController::class, 'registrationVerify'])->name('registrations.verify');
});

// Passenger Routes
Route::middleware(['auth', 'role:passenger'])->prefix('registration')->name('passenger.registration.')->group(function () {
    // Unified Single Page Registration
    Route::get('/create', [\App\Http\Controllers\RegistrationController::class, 'create'])->name('create');
    Route::post('/store', [\App\Http\Controllers\RegistrationController::class, 'store'])->name('store');
    Route::get('/api/buses/{bus}/seats', [\App\Http\Controllers\RegistrationController::class, 'getSeats'])->name('api.seats');

    Route::get('/payment/{registration}', [\App\Http\Controllers\RegistrationController::class, 'payment'])->name('payment');
    Route::get('/dashboard', [\App\Http\Controllers\RegistrationController::class, 'dashboard'])->name('dashboard');
    Route::post('/cancel/{registration}', [\App\Http\Controllers\RegistrationController::class, 'cancel'])->name('cancel');
});

// Check-in Officer Routes
Route::middleware(['auth', 'role:checkin_officer'])->prefix('officer')->name('officer.')->group(function () {
    Route::get('/scan', [\App\Http\Controllers\CheckInController::class, 'scan'])->name('scan');
    Route::post('/verify', [\App\Http\Controllers\CheckInController::class, 'verify'])->name('verify');
});

Route::post('/payment/webhook', [\App\Http\Controllers\PaymentController::class, 'webhook']);
