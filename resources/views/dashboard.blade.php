@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <h1 class="text-2xl font-bold text-slate-800 mb-1">Dashboard Overview</h1>
    <p class="text-gray-500 mb-8 text-sm">Welcome back to the clinic management dashboard.</p>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Patients</p>
                <h3 class="text-2xl font-bold text-slate-800">1,234</h3>
                <p class="text-green-500 text-xs mt-1">+12% from last month</p>
            </div>
            <div class="bg-blue-100 text-blue-600 p-3 rounded-xl"><i class="fa-solid fa-users text-xl"></i></div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Today's Appointments</p>
                <h3 class="text-2xl font-bold text-slate-800">23</h3>
                <p class="text-orange-500 text-xs mt-1">5 pending</p>
            </div>
            <div class="bg-green-100 text-green-600 p-3 rounded-xl"><i class="fa-solid fa-calendar-check text-xl"></i></div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Medicines in Stock</p>
                <h3 class="text-2xl font-bold text-slate-800">156</h3>
                <p class="text-red-500 text-xs mt-1">8 low stock items</p>
            </div>
            <div class="bg-purple-100 text-purple-600 p-3 rounded-xl"><i class="fa-solid fa-pills text-xl"></i></div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Active Programs</p>
                <h3 class="text-2xl font-bold text-slate-800">10</h3>
                <p class="text-gray-400 text-xs mt-1">All services available</p>
            </div>
            <div class="bg-orange-100 text-orange-600 p-3 rounded-xl"><i class="fa-solid fa-bolt text-xl"></i></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-bold text-slate-800 mb-6">Recent Activity</h2>
            <div class="space-y-6">
                @php 
                    $activities = [
                        ['name' => 'Maria Santos', 'task' => 'Pre-natal Check-up', 'time' => '10:30 AM'],
                        ['name' => 'Juan dela Cruz', 'task' => 'Free Consultation', 'time' => '11:00 AM'],
                        ['name' => 'Ana Reyes', 'task' => 'Immunization', 'time' => '11:30 AM'],
                        ['name' => 'Pedro Garcia', 'task' => 'RBS Test', 'time' => '12:00 PM'],
                    ];
                @endphp

                @foreach($activities as $item)
                <div class="flex items-center justify-between border-b border-gray-50 pb-4 last:border-0">
                    <div>
                        <p class="font-bold text-slate-700">{{ $item['name'] }}</p>
                        <p class="text-xs text-gray-400">{{ $item['task'] }}</p>
                    </div>
                    <span class="text-xs font-medium text-gray-400 bg-gray-50 px-3 py-1 rounded-full">{{ $item['time'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-bold text-slate-800 mb-6">Quick Stats</h2>
            <div class="space-y-6">
                <div>
                    <div class="flex justify-between text-xs mb-2">
                        <span class="text-gray-500">Immunization Program</span>
                        <span class="font-bold text-blue-600">85%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: 85%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-xs mb-2">
                        <span class="text-gray-500">Family Planning</span>
                        <span class="font-bold text-green-600">72%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 72%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-xs mb-2">
                        <span class="text-gray-500">Pre-natal Care</span>
                        <span class="font-bold text-purple-600">91%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-purple-500 h-2 rounded-full" style="width: 91%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection