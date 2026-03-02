@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('patients.index') }}" class="text-slate-500 hover:text-blue-600 flex items-center gap-2 transition font-medium">
            <i class="fa-solid fa-arrow-left"></i> Back to Patient Records
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="p-8 flex flex-col md:flex-row items-center gap-6">
            {{-- Updated: Uses first_name for the initial icon --}}
            <div class="w-24 h-24 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 text-3xl font-bold">
                {{ substr($patient->first_name, 0, 1) }}
            </div>
            
            <div class="flex-1 text-center md:text-left">
                <div class="flex items-center justify-center md:justify-start gap-3 mb-1">
                    {{-- Updated: Uses the full_name accessor from the model --}}
                    <h1 class="text-3xl font-bold text-slate-800">{{ $patient->full_name }}</h1>
                    <span class="px-3 py-1 bg-green-100 text-green-600 text-xs font-bold rounded-full uppercase">
                        {{ $patient->status ?? 'Active' }}
                    </span>
                </div>
                <p class="text-slate-500">Patient ID: <span class="font-mono font-bold text-slate-700">{{ $patient->patient_id }}</span></p>
            </div>
            
            <div class="flex gap-3">
                <button onclick="window.print()" class="p-3 bg-slate-100 text-slate-600 rounded-xl hover:bg-slate-200 transition">
                    <i class="fa-solid fa-print"></i>
                </button>
                <button class="px-6 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-200 transition">
                    Create Appointment
                </button>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 border-t border-gray-50 bg-slate-50/50">
            <div class="p-6 text-center border-r border-gray-100">
                <p class="text-xs text-slate-400 font-bold uppercase mb-1">Age</p>
                <p class="text-lg font-bold text-slate-700">{{ $patient->age }} Years Old</p>
            </div>
            <div class="p-6 text-center border-r border-gray-100">
                <p class="text-xs text-slate-400 font-bold uppercase mb-1">Gender</p>
                <p class="text-lg font-bold text-slate-700">{{ $patient->gender }}</p>
            </div>
            <div class="p-6 text-center border-r border-gray-100">
                <p class="text-xs text-slate-400 font-bold uppercase mb-1">Service</p>
                <p class="text-lg font-bold text-blue-600">{{ $patient->service }}</p>
            </div>
            <div class="p-6 text-center">
                <p class="text-xs text-slate-400 font-bold uppercase mb-1">Last Visit</p>
                <p class="text-lg font-bold text-slate-700">{{ \Carbon\Carbon::parse($patient->last_visit)->format('M d, Y') }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-location-dot text-blue-500"></i> Detailed Information
                </h3>
                <div class="grid grid-cols-2 gap-y-6">
                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase">First Name</label>
                        <p class="text-slate-700 font-medium">{{ $patient->first_name }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase">Middle Name</label>
                        <p class="text-slate-700 font-medium">{{ $patient->middle_name ?? '—' }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase">Last Name</label>
                        <p class="text-slate-700 font-medium">{{ $patient->last_name }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase">Suffix</label>
                        <p class="text-slate-700 font-medium">{{ $patient->suffix ?? '—' }}</p>
                    </div>
                    <div class="col-span-2">
                        <label class="text-xs font-bold text-slate-400 uppercase">Address</label>
                        <p class="text-slate-700 font-medium">{{ $patient->address }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Clinical Notes</h3>
                <div class="p-4 bg-slate-50 rounded-2xl border border-dashed border-slate-200 text-center text-slate-400 italic">
                    No clinical notes recorded for this patient yet.
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-slate-800 p-6 rounded-3xl text-white shadow-xl">
                <h4 class="font-bold mb-4">Quick Actions</h4>
                <div class="space-y-3">
                    <button class="w-full py-3 bg-white/10 hover:bg-white/20 rounded-xl transition text-left px-4 flex items-center gap-3">
                        <i class="fa-solid fa-file-medical text-blue-400"></i> Add Lab Result
                    </button>
                    <button class="w-full py-3 bg-white/10 hover:bg-white/20 rounded-xl transition text-left px-4 flex items-center gap-3">
                        <i class="fa-solid fa-capsules text-orange-400"></i> Prescribe Meds
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection