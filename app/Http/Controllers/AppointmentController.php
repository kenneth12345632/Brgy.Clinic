<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function calendar(Request $request)
{
    // Default to today if no date is picked
    $selectedDate = $request->get('date', now()->toDateString());
    
    $appointments = Appointment::with('patient')
        ->whereDate('appointment_date', $selectedDate)
        ->orderBy('appointment_time', 'asc')
        ->get();
return view('patients.calendar', compact('appointments', 'selectedDate'));
}
}