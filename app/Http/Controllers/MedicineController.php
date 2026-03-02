<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index()
    {
        $medicines = Medicine::orderBy('created_at', 'desc')->get();

        // Stats calculated to match your dashboard cards
        $stats = [
            'total_items' => Medicine::count(),
            'total_stock' => Medicine::sum('stock'),
            'low_stock'   => Medicine::whereColumn('stock', '<=', 'min_stock')->count(),
            'categories'  => Medicine::distinct('category')->count('category'),
        ];

        return view('medicines.index', compact('medicines', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|string',
            'stock'       => 'required|integer|min:0',
            'min_stock'   => 'required|integer|min:0',
            'expiry_date' => 'required|date|after:today', // Ensures medicine isn't already expired
        ]);

        // Generate custom Medicine ID
        $nextId = (Medicine::max('id') ?? 0) + 1;
        $validated['medicine_id'] = 'MED-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        Medicine::create($validated);
        return back()->with('success', 'New medicine added successfully!');
    }

    /**
     * Update the specified medicine (The Edit Function)
     */
    public function update(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|string',
            'min_stock'   => 'required|integer|min:0',
            'expiry_date' => 'required|date',
        ]);

        $medicine->update($validated);
        return back()->with('success', "{$medicine->name} has been updated!");
    }

    /**
     * Increase stock levels (The Restock Function)
     */
    public function restock(Request $request, Medicine $medicine)
    {
        $request->validate([
            'amount' => 'required|integer|min:1'
        ]);
        
        $medicine->increment('stock', $request->amount);
        return back()->with('success', "Added {$request->amount} units to {$medicine->name}");
    }
}