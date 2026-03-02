<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\BMICalculatorController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Medicine;

// Guest Routes
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Protected Routes
Route::middleware('auth')->group(function () {
    
   Route::get('/dashboard', [PatientController::class, 'dashboard'])->name('dashboard');

    // 2. DATA ROUTE: This provides the numbers for your auto-update script
    Route::get('/api/stats', function () {
        return response()->json([
            'totalPatients' => Patient::count(),
            'todayAppointments' => Appointment::whereDate('appointment_date', today())->count(),
            'pendingAppointments' => Appointment::where('status', 'pending')->count(),
            'medicineStock' => Medicine::sum('stock'),
            'lowStockCount' => Medicine::where('stock', '<', 10)->count(),
        ]);
    })->name('api.stats');

    // BMI Calculator (Moved inside Auth for security)
    Route::get('/bmi-calculator', [BMICalculatorController::class, 'index'])->name('bmi.index');
    Route::post('/bmi-calculator', [BMICalculatorController::class, 'store'])->name('bmi.store');

    // Medicine Management
    Route::resource('medicines', MedicineController::class);
    Route::post('/medicines/{medicine}/restock', [MedicineController::class, 'restock'])->name('medicines.restock');

    // Patient Management
    Route::resource('patients', PatientController::class);
    Route::get('/patients/{patient}/history', [PatientController::class, 'history'])->name('patients.history');

    // Calendar & Appointments
    Route::get('/calendar', [PatientController::class, 'calendar'])->name('calendar');
    Route::post('/appointments', [PatientController::class, 'storeAppointment'])->name('appointments.store');
    Route::put('/appointments/{id}', [PatientController::class, 'updateAppointment'])->name('appointments.update');
    Route::delete('/appointments/{id}', [PatientController::class, 'destroyAppointment'])->name('appointments.destroy');
    Route::patch('/appointments/{appointment}/status', [PatientController::class, 'updateAppointmentStatus'])->name('appointments.status');

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
});