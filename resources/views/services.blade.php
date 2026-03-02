@extends('layouts.app')

@section('content')
{{-- x-cloak prevents Alpine.js from flickering on page load --}}
<style>[x-cloak] { display: none !important; }</style>

<div class="p-8 bg-slate-50 min-h-screen" x-data="{ activeTab: 'all' }" x-cloak>
    <div class="mb-10">
        <h1 class="text-3xl font-bold text-slate-800">Health Services</h1>
        <p class="text-slate-500 mt-1">Free health services offered by the barangay clinic</p>
    </div>

    {{-- Tab Navigation --}}
    <div class="flex gap-4 mb-8">
        <button @click="activeTab = 'all'" 
                :class="activeTab === 'all' ? 'bg-slate-800 text-white shadow-lg' : 'bg-white text-slate-600 border border-slate-200'"
                class="px-6 py-2 rounded-full font-medium text-sm transition-all duration-200">
            All Services
        </button>
        
        <button @click="activeTab = 'active'" 
                :class="activeTab === 'active' ? 'bg-slate-800 text-white shadow-lg' : 'bg-white text-slate-600 border border-slate-200'"
                class="px-6 py-2 rounded-full font-medium text-sm transition-all duration-200">
            Active Today
        </button>

        <button @click="activeTab = 'stats'" 
                :class="activeTab === 'stats' ? 'bg-slate-800 text-white shadow-lg' : 'bg-white text-slate-600 border border-slate-200'"
                class="px-6 py-2 rounded-full font-medium text-sm transition-all duration-200">
            Statistics
        </button>
    </div>

    {{-- All Services Tab --}}
    <div x-show="activeTab === 'all'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($services as $service)
            <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md transition-shadow">
                <div>
                    <div class="flex justify-between items-start mb-6">
                        <div class="{{ $service['color'] }} w-14 h-14 rounded-2xl flex items-center justify-center text-white shadow-lg">
                            <i class="fa-solid {{ $service['icon'] }} text-2xl"></i>
                        </div>
                        <span class="bg-slate-100 text-slate-600 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                            {{ $service['total'] }} patients
                        </span>
                    </div>

                    <h3 class="text-2xl font-bold text-slate-800 mb-2">{{ $service['name'] }}</h3>
                    <p class="text-slate-400 text-sm leading-relaxed mb-6">{{ $service['desc'] }}</p>

                    <div class="border-t border-slate-50 pt-4 space-y-3">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-400 font-medium">This Month:</span>
                            <span class="text-slate-800 font-bold">{{ $service['month'] }} patients</span>
                        </div>
                        <div class="flex flex-col text-sm">
                            <span class="text-slate-400 font-medium text-xs">Schedule:</span>
                            <span class="text-slate-800 font-bold mt-1">{{ $service['schedule'] }}</span>
                        </div>
                    </div>
                </div>

                {{-- We use a hash if the route doesn't exist yet to prevent 404s --}}
               <a href="{{ route('services.show', $service['name']) }}" class="block w-full text-center px-6 py-3 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition">
    View Details
</a>
            </div>
        @endforeach
    </div>

    {{-- Active Today Tab --}}
    <div x-show="activeTab === 'active'" x-transition class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
        <h2 class="text-xl font-bold text-slate-800 mb-6 px-4">Services Available Today</h2>
        <div class="space-y-2">
            @php $hasActive = false; @endphp
            @foreach($services as $service)
                @if(in_array($currentDay, $service['days']))
                    @php $hasActive = true; @endphp
                    <div class="flex items-center justify-between p-5 rounded-2xl hover:bg-slate-50 transition-all group">
                        <div class="flex items-center gap-5">
                            <div class="{{ $service['color'] }} w-12 h-12 rounded-xl flex items-center justify-center text-white shadow-lg">
                                <i class="fa-solid {{ $service['icon'] }} text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800">{{ $service['name'] }}</h4>
                                <p class="text-slate-400 text-xs">{{ $service['schedule'] }}</p>
                            </div>
                        </div>
                        <span class="px-4 py-1 bg-green-50 text-green-600 text-[10px] font-bold rounded-full uppercase border border-green-100">Active</span>
                    </div>
                @endif
            @endforeach
            @if(!$hasActive)
                <div class="py-12 text-center">
                    <p class="text-slate-400">No services scheduled for today ({{ $currentDay }}).</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Statistics Tab --}}
    <div x-show="activeTab === 'stats'" x-transition class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100">
            <h2 class="text-xl font-bold text-slate-800 mb-8">Service Utilization</h2>
            <div class="space-y-8">
                @foreach($services as $service)
                    <div class="space-y-3">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-600 font-medium">{{ $service['name'] }}</span>
                            <span class="font-bold text-slate-800">{{ $service['total'] }}</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                            @php 
                                // Calculate percentage (max 100%)
                                $percent = $service['total'] > 0 ? min(($service['total'] / 100) * 100, 100) : 0; 
                            @endphp
                            <div class="{{ $service['color'] }} h-2 rounded-full transition-all duration-1000" 
     style="--pg-width: {{ $percent }}%; width: var(--pg-width);">
</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100">
            <h2 class="text-xl font-bold text-slate-800 mb-8">Monthly Activity</h2>
            <div class="divide-y divide-slate-50">
                @foreach($services as $service)
                    <div class="py-5 flex justify-between items-center px-2">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 rounded-full {{ $service['color'] }}"></div>
                            <span class="text-slate-600 font-medium">{{ $service['name'] }}</span>
                        </div>
                        <span class="font-bold text-slate-800">
                            {{ $service['month'] }} 
                            <span class="text-slate-400 font-normal text-xs ml-1">enrolled</span>
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection