<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Appointment;
use App\Models\BmiRecord; // Integrated the BMI Model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PatientController extends Controller
{
    // --- 1. DASHBOARD & UNIFIED ACTIVITY ---
    
    /**
     * This method combines all clinic actions into one feed.
     * It ensures February and previous months reflect if they are recent.
     */
    public function dashboard()
    {
        // 1. Get Latest Patients (Registrations)
        $recentPatients = Patient::latest()->take(10)->get()->map(function($item) {
            return [
                'name' => $item->first_name . ' ' . $item->last_name,
                'type' => 'Medical Service',
                'detail' => $item->service,
                'date' => $item->created_at,
                'icon' => 'fa-user-nurse',
                'color' => 'bg-purple-100 text-purple-600'
            ];
        });

        // 2. Get Latest BMI Calculations
        $recentBmi = BmiRecord::latest()->take(10)->get()->map(function($item) {
            return [
                'name' => $item->patient_name,
                'type' => 'BMI Check',
                'detail' => "Result: {$item->bmi} ({$item->category})",
                'date' => $item->created_at,
                'icon' => 'fa-calculator',
                'color' => 'bg-blue-100 text-blue-600'
            ];
        });

        // 3. Get Latest Appointments
        $recentApps = Appointment::with('patient')->latest()->take(10)->get()->map(function($item) {
            return [
                'name' => $item->patient ? ($item->patient->first_name . ' ' . $item->patient->last_name) : 'Walk-in',
                'type' => 'Appointment',
                'detail' => 'Scheduled: ' . $item->service_type,
                'date' => $item->created_at,
                'icon' => 'fa-calendar-check',
                'color' => 'bg-emerald-100 text-emerald-600'
            ];
        });

        // MERGE & SORT: This removes the "Month Wall" and shows all recent history
        $allActivity = collect()
            ->merge($recentPatients)
            ->merge($recentBmi)
            ->merge($recentApps)
            ->sortByDesc('date')
            ->take(10); 

        // Global Stats for the Dashboard Cards
        $stats = [
            'total_patients' => Patient::count(),
            'total_calcs'    => BmiRecord::count(),
            'high_risk'      => BmiRecord::whereIn('category', ['Overweight', 'Obese'])->count(),
            'today_apps'     => Appointment::whereDate('appointment_date', Carbon::today())->count(),
        ];

        return view('dashboard', compact('allActivity', 'stats'));
    }

    // --- 2. PATIENT MANAGEMENT ---
    
    public function index()
    {
        $patients = Patient::latest()->get();
        return view('patients', compact('patients'));
    }

    public function show(Patient $patient)
    {
        return view('patients.show', compact('patient'));
    }

    public function history(Patient $patient)
    {
        $records = Appointment::where('patient_id', $patient->id)
                    ->orWhere('service_type', $patient->service)
                    ->latest()
                    ->get();
        return view('patients.history', compact('patient', 'records'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'  => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name'   => 'required|string|max:255',
            'suffix'      => 'nullable|string|max:255',
            'birthday'    => 'required|date',
            'gender'      => 'required|string',
            'address'     => 'required|string',
            'service'     => 'required|string',
            'last_visit'  => 'required|date',
        ]);

        $nextId = (Patient::max('id') ?? 0) + 1;
        $validated['patient_id'] = 'P-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        Patient::create($validated);
        return back()->with('success', 'Patient added successfully!');
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'first_name'  => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name'   => 'required|string|max:255',
            'suffix'      => 'nullable|string|max:255',
            'birthday'    => 'required|date',
            'gender'      => 'required|string',
            'address'     => 'required|string',
            'service'     => 'required|string',
            'last_visit'  => 'required|date',
        ]);

        $patient->update($validated);
        return back()->with('success', 'Patient record updated successfully!');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return back()->with('success', 'Patient deleted successfully!');
    }

    // --- 3. CALENDAR METHODS ---

    public function calendar()
    {
        $appointments = Appointment::with('patient')->get();
        $allPatients = Patient::orderBy('first_name', 'asc')->get();
        return view('calendar', compact('appointments', 'allPatients'));
    }

    public function storeAppointment(Request $request)
    {
        $request->validate([
            'service_type' => 'required|string',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
        ]);

        Appointment::create($request->all());
        return redirect()->back()->with('success', 'Event added!');
    }

    // --- 4. SERVICES DASHBOARD ---

    public function indexServices()
    {
        $counts = Patient::select('service', DB::raw('count(*) as total'))
            ->groupBy('service')
            ->pluck('total', 'service')
            ->toArray();

        $uiSettings = [
            'Immunization'     => ['icon' => 'fa-syringe', 'color' => 'bg-blue-500', 'day' => 'Wednesday'],
            'Family Planning'  => ['icon' => 'fa-house-chimney-medical', 'color' => 'bg-purple-500', 'day' => 'Friday'],
            'Deworming'        => ['icon' => 'fa-bug', 'color' => 'bg-emerald-500', 'day' => 'Monday'],
            'Supplementation'  => ['icon' => 'fa-pills', 'color' => 'bg-orange-400', 'day' => 'Thursday'],
            'Pre-natal'        => ['icon' => 'fa-person-pregnant', 'color' => 'bg-rose-500', 'day' => 'Tuesday'],
            'Ferrous'          => ['icon' => 'fa-file-medical', 'color' => 'bg-slate-500', 'day' => 'Monday'],
            'Free Consultation'=> ['icon' => 'fa-stethoscope', 'color' => 'bg-indigo-500', 'day' => 'Daily'],
            'RBS (Random Blood Sugar)' => ['icon' => 'fa-droplet', 'color' => 'bg-red-400', 'day' => 'Wednesday'],
            'Feeding Program'  => ['icon' => 'fa-bowl-food', 'color' => 'bg-lime-500', 'day' => 'Friday'],
            'TB DOTS'          => ['icon' => 'fa-lungs', 'color' => 'bg-red-600', 'day' => 'Thursday'],
        ];

        $services = [];
        foreach ($uiSettings as $name => $style) {
            $services[] = [
                'name'     => $name,
                'icon'     => $style['icon'],
                'color'    => $style['color'],
                'desc'     => "Essential $name services.",
                'total'    => $counts[$name] ?? 0,
                'month'    => Patient::where('service', $name)->whereMonth('created_at', now()->month)->count(),
                'schedule' => $style['day'] . ', 8:00 AM - 5:00 PM',
                'days'     => [$style['day']]
            ];
        }

        $currentDay = now()->format('l');
        return view('services', compact('services', 'currentDay'));
    }

    public function showServiceDetails($serviceName)
    {
        $patients = Patient::where('service', $serviceName)->latest()->get();
        return view('patients', [
            'patients' => $patients,
            'title'    => "Patients under " . $serviceName
        ]);
    }
}