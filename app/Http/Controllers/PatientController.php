<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\BmiRecord;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PatientController extends Controller
{
    // --- 1. DASHBOARD ---
    public function dashboard()
    {
        $today_apps = Appointment::whereDate('appointment_date', Carbon::today())
                        ->with('patient')
                        ->get();

        $stats = [
            'total_patients' => Patient::count(),
            'total_calcs'    => BmiRecord::count(),
            'high_risk'      => BmiRecord::whereIn('category', ['Overweight', 'Obese'])->count(),
            'today_apps'     => $today_apps->count(),
        ];

        $allActivity = $this->getUnifiedActivity();

        return view('dashboard', compact('allActivity', 'stats', 'today_apps'));
    }

    // --- 2. PATIENT MANAGEMENT ---
    public function index()
    {
        $patients = Patient::latest()->get();
        return view('patients', compact('patients'));
    }

    // --- 3. STORE NEW PATIENT ---
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'  => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name'   => 'required|string|max:255',
            'birthday'    => 'required|date',
            'gender'      => 'required|string',
            'service'     => 'required|string',
            'address'     => 'required|string',
        ]);

        // Generate unique Patient ID
        $validated['patient_id'] = 'PAT-' . strtoupper(substr(uniqid(), -5));

        Patient::create($validated);

        return redirect()->route('patients.index')->with('success', 'Patient added successfully!');
    }

    // --- 4. SERVICES DASHBOARD ---
    public function indexServices()
    {
        $counts = Patient::select('service', DB::raw('count(*) as total'))
            ->groupBy('service')
            ->pluck('total', 'service')
            ->toArray();

        $uiSettings = [
            'Immunization'      => ['icon' => 'fa-syringe', 'color' => 'bg-blue-500', 'day' => 'Wednesday'],
            'Family Planning'   => ['icon' => 'fa-house-chimney-medical', 'color' => 'bg-purple-500', 'day' => 'Friday'],
            'Deworming'         => ['icon' => 'fa-bug', 'color' => 'bg-emerald-500', 'day' => 'Monday'],
            'Supplementation'   => ['icon' => 'fa-pills', 'color' => 'bg-orange-400', 'day' => 'Thursday'],
            'Pre-natal'         => ['icon' => 'fa-person-pregnant', 'color' => 'bg-rose-500', 'day' => 'Tuesday'],
            'Ferrous'           => ['icon' => 'fa-file-medical', 'color' => 'bg-slate-500', 'day' => 'Monday'],
            'Free Consultation' => ['icon' => 'fa-stethoscope', 'color' => 'bg-indigo-500', 'day' => 'Daily'],
            'RBS (Random Blood Sugar)' => ['icon' => 'fa-droplet', 'color' => 'bg-red-400', 'day' => 'Wednesday'],
            'Feeding Program'   => ['icon' => 'fa-bowl-food', 'color' => 'bg-lime-500', 'day' => 'Friday'],
            'TB DOTS'           => ['icon' => 'fa-lungs', 'color' => 'bg-red-600', 'day' => 'Thursday'],
        ];

        $services = [];

        foreach ($uiSettings as $name => $style) {
            $services[] = [
                'name'     => $name,
                'icon'     => $style['icon'],
                'color'    => $style['color'],
                'desc'     => "Essential $name services.",
                'total'    => $counts[$name] ?? 0,
                'month'    => Patient::where('service', $name)
                                ->whereMonth('created_at', now()->month)
                                ->count(),
                'schedule' => $style['day'] . ', 8:00 AM - 5:00 PM',
                'days'     => [$style['day']]
            ];
        }

        $currentDay = now()->format('l');

        return view('services', compact('services', 'currentDay'));
    }

    // --- 5. SHOW SERVICE DETAILS (FIX FOR YOUR ERROR) ---
   public function showServiceDetails($serviceName)
{
    // Fetch the data
    $patients = Patient::where('service', $serviceName)->get();

    // Pass the data to the view
    return view('services.details', [
        'serviceName' => $serviceName,
        'patients'    => $patients // This name must match your @foreach in the view
    ]);
}
    // --- 6. CALENDAR & APPOINTMENTS ---
    public function calendar()
    {
        $appointments = Appointment::with('patient')->get();
        $allPatients = Patient::orderBy('last_name', 'asc')->get();

        return view('calendar', compact('appointments', 'allPatients'));
    }

    // --- 7. STORE APPOINTMENT ---
    public function storeAppointment(Request $request)
    {
        $validated = $request->validate([
            'patient_id'       => 'nullable|exists:patients,patient_id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'service_type'     => 'required|string|max:255',
            'status'           => 'required|in:pending,confirmed,cancelled',
        ]);

        Appointment::create($validated);

        return redirect()->route('calendar')->with('success', 'Event added successfully!');
    }

    // --- 8. UPDATE EXISTING PATIENT ---
    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'first_name'  => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name'   => 'required|string|max:255',
            'birthday'    => 'required|date',
            'gender'      => 'required|string',
            'service'     => 'required|string',
            'address'     => 'required|string',
        ]);

        $patient->update($validated);

        return redirect()->route('patients.index')->with('success', 'Patient record updated!');
    }

    // --- 9. SHOW SINGLE PATIENT ---
    public function show(Patient $patient)
    {
        return view('patients.show', compact('patient'));
    }

    // --- HELPER FOR DASHBOARD ACTIVITY ---
    private function getUnifiedActivity()
    {
        $recentPatients = Patient::latest()->take(10)->get()->map(fn($item) => [
            'name' => "$item->first_name $item->last_name",
            'type' => 'Medical Service',
            'detail' => $item->service,
            'date' => $item->created_at,
            'icon' => 'fa-user-nurse',
            'color' => 'bg-purple-100 text-purple-600'
        ]);

        $recentBmi = BmiRecord::latest()->take(10)->get()->map(fn($item) => [
            'name' => $item->patient_name,
            'type' => 'BMI Check',
            'detail' => "Result: $item->bmi ($item->category)",
            'date' => $item->created_at,
            'icon' => 'fa-calculator',
            'color' => 'bg-blue-100 text-blue-600'
        ]);

        return collect()
            ->merge($recentPatients)
            ->merge($recentBmi)
            ->sortByDesc('date')
            ->take(10);
    }
}