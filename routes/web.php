<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\BMICalculatorController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use App\Models\Medicine;
use App\Models\BmiRecord; // Added this since you are tracking BMI now

// Guest Routes
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Protected Routes
Route::middleware('auth')->group(function () {
    
    Route::get('/dashboard', [PatientController::class, 'dashboard'])->name('dashboard');

    // UPDATED: Removed Appointment logic from API stats
    Route::get('/api/stats', function () {
        return response()->json([
            'totalPatients' => Patient::count(),
            'totalBmi' => BmiRecord::count(), // Replaced appointments with BMI count
            'medicineStock' => Medicine::sum('stock'),
            'lowStockCount' => Medicine::where('stock', '<', 10)->count(),
        ]);
    })->name('api.stats');

    // BMI Calculator
    Route::get('/bmi-calculator', [BMICalculatorController::class, 'index'])->name('bmi.index');
    Route::post('/bmi-calculator', [BMICalculatorController::class, 'store'])->name('bmi.store');

    // Medicine Management
    Route::resource('medicines', MedicineController::class);
    Route::post('/medicines/{medicine}/restock', [MedicineController::class, 'restock'])->name('medicines.restock');

    // Patient Management
    Route::resource('patients', PatientController::class);
    // Removed patients.history as it relied on appointment records

    // Health Services
    Route::get('/services', [PatientController::class, 'indexServices'])->name('services.index');
    Route::get('/services/{serviceName}', [PatientController::class, 'showServiceDetails'])->name('services.show');
    
    // Logout
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
    // Add these back to routes/web.php
Route::get('/calendar', [PatientController::class, 'calendar'])->name('calendar');
Route::post('/appointments', [PatientController::class, 'storeAppointment'])->name('appointments.store');
});