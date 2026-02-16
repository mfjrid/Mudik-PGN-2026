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
Route::middleware(['auth', 'role:superadmin|admin_kc'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('buses', \App\Http\Controllers\BusController::class);
    Route::get('/registrations', [\App\Http\Controllers\BusController::class, 'registrations'])->name('registrations.index');
    Route::get('/registrations/{registration}', [\App\Http\Controllers\BusController::class, 'registrationShow'])->name('registrations.show');
    Route::post('/registrations/{registration}/verify', [\App\Http\Controllers\BusController::class, 'registrationVerify'])->name('registrations.verify');
});

// Passenger Routes
Route::middleware(['auth', 'role:passenger'])->prefix('registration')->name('passenger.registration.')->group(function () {
    Route::get('/step1', [\App\Http\Controllers\RegistrationController::class, 'step1'])->name('step1');
    Route::post('/step1', [\App\Http\Controllers\RegistrationController::class, 'postStep1']);
    Route::get('/step2', [\App\Http\Controllers\RegistrationController::class, 'step2'])->name('step2');
    Route::post('/step2', [\App\Http\Controllers\RegistrationController::class, 'postStep2'])->name('step2.post');
    Route::get('/step3', [\App\Http\Controllers\RegistrationController::class, 'step3'])->name('step3');
    Route::post('/step3', [\App\Http\Controllers\RegistrationController::class, 'postStep3'])->name('step3.post');
    Route::get('/success/{registration}', [\App\Http\Controllers\RegistrationController::class, 'success'])->name('success');
    Route::post('/cancel/{registration}', [\App\Http\Controllers\RegistrationController::class, 'cancel'])->name('cancel');
});

// Check-in Officer Routes
Route::middleware(['auth', 'role:checkin_officer'])->prefix('officer')->name('officer.')->group(function () {
    Route::get('/scan', [\App\Http\Controllers\CheckInController::class, 'scan'])->name('scan');
    Route::post('/verify', [\App\Http\Controllers\CheckInController::class, 'verify'])->name('verify');
});

Route::post('/payment/webhook', [\App\Http\Controllers\PaymentController::class, 'webhook']);
