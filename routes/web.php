<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Guest Routes
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Protected Routes
Route::middleware('auth')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Patient Management
    Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
    Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');
    Route::get('/patients/{patient}', [PatientController::class, 'show'])->name('patients.show');
    Route::put('/patients/{patient}', [PatientController::class, 'update'])->name('patients.update');
    Route::delete('/patients/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');
    Route::get('/patients/{patient}/history', [PatientController::class, 'history'])->name('patients.history');

    // Calendar & Appointments
    Route::get('/calendar', [PatientController::class, 'calendar'])->name('calendar');
    Route::post('/appointments', [PatientController::class, 'storeAppointment'])->name('appointments.store');
    Route::patch('/appointments/{appointment}/status', [PatientController::class, 'updateAppointmentStatus'])->name('appointments.status');
    Route::delete('/appointments/{appointment}', [PatientController::class, 'destroyAppointment'])->name('appointments.destroy');

    // Logout
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
     // Calendar View
Route::get('/calendar', [PatientController::class, 'calendar'])->name('calendar');

// Store New (POST)
Route::post('/appointments', [PatientController::class, 'storeAppointment'])->name('appointments.store');

// Update Existing (PUT) - THIS IS LIKELY WHAT IS MISSING
Route::put('/appointments/{id}', [PatientController::class, 'updateAppointment'])->name('appointments.update');

// Delete Existing (DELETE)
Route::delete('/appointments/{id}', [PatientController::class, 'destroyAppointment'])->name('appointments.destroy');
    
});