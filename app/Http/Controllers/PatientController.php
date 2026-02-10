<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Appointment;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    // --- PATIENT MANAGEMENT ---

    public function index()
    {
        $patients = Patient::latest()->get();
        return view('patients', compact('patients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer',
            'gender' => 'required|string',
            'address' => 'required|string',
            'service' => 'required|string',
            'last_visit' => 'required|date',
        ]);

        $nextId = (Patient::max('id') ?? 0) + 1;
        $validated['patient_id'] = 'P-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        Patient::create($validated);
        return back()->with('success', 'Patient added successfully!');
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer',
            'gender' => 'required|string',
            'address' => 'required|string',
            'service' => 'required|string',
            'last_visit' => 'required|date',
        ]);

        $patient->update($validated);
        return back()->with('success', 'Patient record updated successfully!');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return back()->with('success', 'Patient deleted successfully!');
    }

    public function show(Patient $patient)
    {
        return view('patients.show', compact('patient'));
    }

    public function history(Patient $patient)
    {
        return view('patients.history', compact('patient'));
    }

    // --- CALENDAR & EVENT METHODS ---

    public function calendar()
    {
        // Fetch all appointments for the month view
        $appointments = Appointment::with('patient')->get();
        $allPatients = Patient::orderBy('name', 'asc')->get();

        return view('calendar', compact('appointments', 'allPatients'));
    }

    public function storeAppointment(Request $request)
    {
        $request->validate([
            'service_type' => 'required|string|max:255',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
        ]);

        Appointment::create([
            'patient_id' => null, // General event focus
            'service_type' => $request->service_type,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'status' => 'confirmed',
        ]);

        return redirect()->back()->with('success', 'Event added to calendar!');
    }

    public function updateAppointment(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        
        $request->validate([
            'service_type' => 'required|string|max:255',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
        ]);

        $appointment->update([
            'service_type' => $request->service_type,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
        ]);

        return redirect()->back()->with('success', 'Event updated successfully!');
    }

    public function destroyAppointment($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return redirect()->back()->with('success', 'Event removed successfully!');
    }
}