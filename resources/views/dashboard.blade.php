@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <h1 class="text-2xl font-bold text-slate-800 mb-1">Dashboard Overview</h1>
    <p class="text-gray-500 mb-8 text-sm">Welcome back to the clinic management dashboard.</p>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Total Patients --}}
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Patients</p>
                <h3 id="stat-total-patients" class="text-2xl font-bold text-slate-800">{{ $stats['total_patients'] }}</h3>
                <p class="text-green-500 text-xs mt-1">+12% from last month</p>
            </div>
            <div class="bg-blue-100 text-blue-600 p-3 rounded-xl"><i class="fa-solid fa-users text-xl"></i></div>
        </div>

        {{-- Today's Appointments --}}
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Today's Appointments</p>
                <h3 id="stat-today-appointments" class="text-2xl font-bold text-slate-800">{{ $stats['today_apps'] }}</h3>
                <p id="stat-pending-label" class="text-orange-500 text-xs mt-1">Check calendar</p>
            </div>
            <div class="bg-green-100 text-green-600 p-3 rounded-xl"><i class="fa-solid fa-calendar-check text-xl"></i></div>
        </div>

        {{-- BMI Records (Replacing Medicine Stock for your specific use case) --}}
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">BMI Calculations</p>
                <h3 id="stat-bmi-records" class="text-2xl font-bold text-slate-800">{{ $stats['total_calcs'] }}</h3>
                <p id="stat-high-risk-label" class="text-red-500 text-xs mt-1">{{ $stats['high_risk'] }} high risk cases</p>
            </div>
            <div class="bg-purple-100 text-purple-600 p-3 rounded-xl"><i class="fa-solid fa-calculator text-xl"></i></div>
        </div>

        {{-- Active Programs --}}
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Active Programs</p>
                <h3 class="text-2xl font-bold text-slate-800">10</h3>
                <p class="text-gray-400 text-xs mt-1">All services active</p>
            </div>
            <div class="bg-orange-100 text-orange-600 p-3 rounded-xl"><i class="fa-solid fa-bolt text-xl"></i></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Unified Recent Activity List --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-bold text-slate-800 mb-6 uppercase text-xs tracking-widest">Recent Activity (Including Feb)</h2>
            <div id="recent-activity-list" class="space-y-6">
                @forelse($allActivity as $activity)
                    <div class="flex items-center gap-4">
                        <div class="{{ $activity['color'] }} p-3 rounded-xl w-12 h-12 flex items-center justify-center shrink-0">
                            <i class="fa-solid {{ $activity['icon'] }}"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between">
                                <p class="text-sm font-bold text-slate-800">{{ $activity['name'] }}</p>
                                <span class="text-[10px] text-gray-400 font-bold uppercase">{{ \Carbon\Carbon::parse($activity['date'])->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-gray-500">{{ $activity['type'] }} • {{ $activity['detail'] }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-400 text-sm italic">No recent activity found.</p>
                @endforelse
            </div>
        </div>

        {{-- Side Progress Stats --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-bold text-slate-800 mb-6">Service Utilization</h2>
            <div class="space-y-6">
                @php
                    $thresholds = [
                        ['label' => 'Immunization', 'percent' => 85, 'color' => 'bg-blue-500'],
                        ['label' => 'Prenatal Care', 'percent' => 64, 'color' => 'bg-rose-500'],
                        ['label' => 'Nutrition/BMI', 'percent' => 45, 'color' => 'bg-indigo-500'],
                    ];
                @endphp
                @foreach($thresholds as $prog)
                <div>
                    <div class="flex justify-between text-xs mb-2">
                        <span class="text-gray-500">{{ $prog['label'] }}</span>
                        <span class="font-bold text-slate-700">{{ $prog['percent'] }}%</span>
                    </div>
                   <div class="{{ $prog['color'] }} h-1.5 rounded-full" 
     style="--progress-width: {{ $prog['percent'] }}%; width: var(--progress-width);">
</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function refreshStats() {
        // This keeps the numbers at the top fresh without reloading the whole page
        fetch("{{ route('api.stats') }}")
            .then(response => response.json())
            .then(data => {
                document.getElementById('stat-total-patients').innerText = data.totalPatients;
                document.getElementById('stat-today-appointments').innerText = data.todayAppointments;
                
                if(document.getElementById('stat-bmi-records')) {
                    document.getElementById('stat-bmi-records').innerText = data.totalBmi;
                }
            })
            .catch(error => console.warn('Live stats update paused. Refresh manually.'));
    }

    // Update numbers every 30 seconds
    setInterval(refreshStats, 30000);
</script>
@endpush