@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('patients.index') }}" class="text-slate-500 hover:text-blue-600 flex items-center gap-2 transition font-medium">
            <i class="fa-solid fa-arrow-left"></i> Back to Patient List
        </a>
        <div class="flex gap-2">
            <button onclick="window.print()" class="bg-white border border-gray-200 text-slate-600 px-4 py-2 rounded-xl text-sm font-bold hover:bg-gray-50">
                <i class="fa-solid fa-print mr-2"></i> Print History
            </button>
            <button class="bg-blue-600 text-white px-4 py-2 rounded-xl text-sm font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transition">
                + Add Clinical Note
            </button>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 bg-slate-800 text-white flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <span class="text-blue-400 text-xs font-bold uppercase tracking-widest">Medical Record</span>
                <h1 class="text-3xl font-bold">{{ $patient->name }}</h1>
                <p class="text-slate-400 text-sm mt-1">
                    ID: <span class="text-white font-mono">{{ $patient->patient_id }}</span> | 
                    {{ $patient->gender }} | 
                    {{ $patient->age }} Years Old
                </p>
            </div>
            <div class="bg-slate-700/50 p-4 rounded-2xl border border-slate-600 w-full md:w-auto">
                <p class="text-xs uppercase font-bold text-slate-400 mb-1">Current Treatment/Service</p>
                <p class="text-xl font-bold text-blue-400">{{ $patient->service }}</p>
            </div>
        </div>

        <div class="p-8">
            <h2 class="text-lg font-bold text-slate-800 mb-8 flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-blue-500"></i> Clinical Visit History
            </h2>

            <div class="relative">
                <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-slate-100"></div>

                <div class="relative pl-12 pb-12">
                    <div class="absolute left-2 top-1 w-4 h-4 bg-blue-600 rounded-full border-4 border-white ring-1 ring-blue-600"></div>
                    
                    <div class="bg-slate-50 border border-slate-100 rounded-2xl p-6 shadow-sm">
                        <div class="flex flex-col md:flex-row justify-between items-start mb-4 gap-2">
                            <div>
                                <span class="bg-blue-100 text-blue-700 text-[10px] font-black px-2 py-1 rounded uppercase tracking-tighter">Latest Visit</span>
                                <h4 class="text-lg font-bold text-slate-800 mt-2">Recorded Service: {{ $patient->service }}</h4>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-bold text-slate-500 block">
                                    <i class="fa-regular fa-calendar-check mr-1"></i>
                                    {{ \Carbon\Carbon::parse($patient->last_visit)->format('F d, Y') }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="text-slate-600 space-y-3 text-sm leading-relaxed">
                            <p>Patient was attended for <strong>{{ $patient->service }}</strong>. The current medical record indicates the patient resides at <strong>{{ $patient->address }}</strong>.</p>
                            <div class="bg-white p-3 rounded-lg border border-slate-200 italic text-slate-500 text-xs">
                                Note: This is the initial automated record entry based on the patient registration.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative pl-12">
                    <div class="absolute left-3 top-1 w-2.5 h-2.5 bg-slate-300 rounded-full border-2 border-white"></div>
                    <div class="text-slate-400 text-sm">
                        <span class="font-bold">Account Opened:</span> {{ $patient->created_at->format('M d, Y') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection