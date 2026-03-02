<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BmiRecord;
use Illuminate\Support\Facades\Log;

class BMICalculatorController extends Controller
{
    public function index()
    {
        // Fetch all records
        $calculations = BmiRecord::latest()->get();

        // Calculate stats for the dashboard cards
        $totalCalculations = $calculations->count();
        $normalCount = $calculations->where('category', 'Normal weight')->count();
        
        // High Risk includes both Overweight and Obese
        $highRiskCount = $calculations->filter(function ($item) {
            return in_array($item->category, ['Overweight', 'Obese']);
        })->count();

        // Total unique patients (placeholder logic or count unique names/IDs)
        $patientCount = $calculations->unique('patient_name')->count();

        return view('bmi.index', compact('calculations', 'totalCalculations', 'normalCount', 'highRiskCount', 'patientCount'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'patient_name' => 'required|string|max:255',
                'age'          => 'required|integer',
                'gender'       => 'required|string',
                'weight'       => 'required|numeric',
                'height'       => 'required|numeric',
                'bmi'          => 'required|numeric',
                'category'     => 'required|string',
            ]);

            // Generate a Patient ID based on record count
            $validated['date'] = now()->format('Y-m-d');
            $validated['patient_id'] = 'P-' . str_pad(BmiRecord::count() + 1, 3, '0', STR_PAD_LEFT);

            BmiRecord::create($validated);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}