@extends('layouts.app')

@section('content')
<div class="p-8 bg-slate-50 min-h-screen" x-data="bmiApp()">
    {{-- Header Section --}}
    <div class="mb-10">
        <h1 class="text-3xl font-black text-slate-800 tracking-tight">BMI Calculator</h1>
        <p class="text-slate-500 font-medium">Calculate and track Body Mass Index for patients</p>
    </div>

    {{-- Stats Cards Row --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex justify-between items-center">
            <div>
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">Total Calcs</p>
                <p class="text-3xl font-black text-slate-800 mt-1">{{ count($calculations) }}</p>
            </div>
            <div class="text-blue-500 bg-blue-50 w-12 h-12 rounded-2xl flex items-center justify-center">
                <i class="fa-solid fa-calculator text-lg"></i>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex justify-between items-center">
            <div>
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">Normal</p>
                <p class="text-3xl font-black text-green-500 mt-1">{{ $normalCount ?? 0 }}</p>
            </div>
            <div class="text-green-500 bg-green-50 w-12 h-12 rounded-2xl flex items-center justify-center">
                <i class="fa-solid fa-heart-pulse text-lg"></i>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex justify-between items-center">
            <div>
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">High Risk</p>
                <p class="text-3xl font-black text-orange-500 mt-1">{{ $highRiskCount ?? 0 }}</p>
            </div>
            <div class="text-orange-500 bg-orange-50 w-12 h-12 rounded-2xl flex items-center justify-center">
                <i class="fa-solid fa-triangle-exclamation text-lg"></i>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex justify-between items-center">
            <div>
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">History</p>
                <p class="text-3xl font-black text-purple-500 mt-1">{{ count($calculations) }}</p>
            </div>
            <div class="text-purple-500 bg-purple-50 w-12 h-12 rounded-2xl flex items-center justify-center">
                <i class="fa-solid fa-clock-rotate-left text-lg"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Calculation Form --}}
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-xl font-black text-slate-800 uppercase tracking-tight">Patient Entry</h2>
                    <div class="flex bg-slate-100 p-1 rounded-xl">
                        <button @click="unit = 'metric'" :class="unit === 'metric' ? 'bg-white shadow-sm text-blue-600' : 'text-slate-400'" class="px-6 py-2 rounded-lg font-bold text-xs transition-all">Metric</button>
                        <button @click="unit = 'imperial'" :class="unit === 'imperial' ? 'bg-white shadow-sm text-blue-600' : 'text-slate-400'" class="px-6 py-2 rounded-lg font-bold text-xs transition-all">Imperial</button>
                    </div>
                </div>

                <div class="space-y-6">
                    {{-- Patient Name Input --}}
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 mb-2 ml-1">Patient Full Name</label>
                        <input type="text" x-model="patientName" class="w-full bg-slate-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-blue-500 font-bold" placeholder="John Doe">
                    </div>

                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 mb-2 ml-1">Age (Years)</label>
                            <input type="number" x-model="age" class="w-full bg-slate-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-blue-500 font-bold" placeholder="37">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 mb-2 ml-1">Gender</label>
                            <select x-model="gender" class="w-full bg-slate-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-blue-500 font-bold text-slate-700">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 mb-2 ml-1" x-text="unit === 'metric' ? 'Weight (kg)' : 'Weight (lbs)'"></label>
                            <input type="number" x-model="weight" class="w-full bg-slate-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-blue-500 font-bold" placeholder="0.0">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 mb-2 ml-1" x-text="unit === 'metric' ? 'Height (cm)' : 'Height (inches)'"></label>
                            <input type="number" x-model="height" class="w-full bg-slate-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-blue-500 font-bold" placeholder="0.0">
                        </div>
                    </div>
                </div>

                <button @click="calculateBMI()" class="w-full bg-blue-600 text-white py-5 rounded-[1.5rem] font-black text-xs uppercase tracking-[0.2em] hover:bg-blue-700 transition shadow-lg shadow-blue-100 flex items-center justify-center gap-3">
                    <i class="fa-solid fa-calculator"></i> Calculate & Save BMI
                </button>
            </div>

            {{-- BMI Result Display --}}
            <template x-if="result">
                <div class="bg-white rounded-[2.5rem] p-10 border border-slate-100 shadow-sm text-center transform transition-all" x-transition>
                    <p class="text-slate-400 font-black text-[10px] uppercase tracking-[0.2em]">Patient BMI Result</p>
                    <p class="text-8xl font-black text-slate-800 my-6 tracking-tighter" x-text="result"></p>
                    <div class="inline-block px-6 py-2 rounded-xl text-sm font-black uppercase tracking-widest mb-10" :class="categoryClass" x-text="category"></div>
                    
                    <div class="max-w-md mx-auto relative mb-12">
                        <div class="h-2 w-full flex rounded-full overflow-hidden shadow-inner bg-slate-100">
                            <div class="h-full bg-blue-400" style="width: 25%"></div>
                            <div class="h-full bg-green-400" style="width: 25%"></div>
                            <div class="h-full bg-orange-400" style="width: 25%"></div>
                            <div class="h-full bg-red-400" style="width: 25%"></div>
                        </div>
                        <div class="absolute -top-1 transition-all duration-1000 ease-out" :style="`left: ${gaugePosition}%`" style="transform: translateX(-50%)">
                            <i class="fa-solid fa-caret-down text-slate-800 text-xl"></i>
                        </div>
                    </div>

                    <div class="bg-slate-50 p-8 rounded-3xl border border-slate-100 flex items-center gap-6 text-left">
                        <div class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center text-blue-500 shrink-0 shadow-sm">
                            <i class="fa-solid fa-check-circle text-lg"></i>
                        </div>
                        <div>
                            <p class="text-slate-800 font-black text-xs uppercase mb-1">Status: Saved to Records</p>
                            <p class="text-slate-500 font-medium leading-relaxed text-sm" x-text="advice"></p>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        {{-- Info Sidebar --}}
        <div class="space-y-6">
            <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
                <h3 class="font-black text-slate-800 uppercase text-xs tracking-widest mb-8">Reference Scale</h3>
                <div class="space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                        <div>
                            <p class="text-sm font-black text-slate-800">Underweight</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Below 18.5</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-3 h-3 rounded-full bg-green-500"></div>
                        <div>
                            <p class="text-sm font-black text-slate-800">Normal weight</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">18.5 - 24.9</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-3 h-3 rounded-full bg-orange-500"></div>
                        <div>
                            <p class="text-sm font-black text-slate-800">Overweight</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">25.0 - 29.9</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-3 h-3 rounded-full bg-red-500"></div>
                        <div>
                            <p class="text-sm font-black text-slate-800">Obese</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">30.0 +</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- History Table --}}
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 mt-12 overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex justify-between items-center">
            <h2 class="text-xl font-black text-slate-800 uppercase tracking-tight">Recent Records</h2>
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Updated {{ now()->format('h:i A') }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-50">
                        <th class="px-8 py-6">Patient ID</th>
                        <th class="px-4 py-6">Name</th>
                        <th class="px-4 py-6">Details</th>
                        <th class="px-4 py-6 text-center">BMI Score</th>
                        <th class="px-8 py-6 text-right">Category</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($calculations as $calc)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-8 py-5 font-black text-blue-600 text-xs uppercase tracking-tighter">
                            {{ $calc->patient_id }}
                        </td>
                        <td class="px-4 py-5 font-bold text-slate-800 text-sm">
                            {{ $calc->patient_name }}
                        </td>
                        <td class="px-4 py-5">
                            <span class="text-slate-500 text-xs font-bold uppercase">{{ $calc->gender }}</span>
                            <span class="text-slate-300 mx-1">•</span>
                            <span class="text-slate-500 text-xs font-medium">{{ $calc->age }}y</span>
                        </td>
                        <td class="px-4 py-5 text-center font-black text-slate-800 text-lg tracking-tighter">
                            {{ number_format($calc->bmi, 1) }}
                        </td>
                        <td class="px-8 py-5 text-right">
                            <span class="px-4 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest
                                {{ str_contains($calc->category, 'Normal') ? 'bg-green-50 text-green-500' : 
                                   (str_contains($calc->category, 'Underweight') ? 'bg-blue-50 text-blue-500' : 'bg-orange-50 text-orange-500') }}">
                                {{ $calc->category }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-10 text-center text-slate-400 font-medium">No records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function bmiApp() {
    return {
        unit: 'metric',
        patientName: '',
        age: '',
        gender: 'male',
        weight: '',
        height: '',
        result: null,
        category: '',
        categoryClass: '',
        advice: '',
        gaugePosition: 0,

        calculateBMI() {
            let w = parseFloat(this.weight);
            let h = parseFloat(this.height);
            
            if (!w || !h || !this.patientName) {
                alert("Please enter patient name, height, and weight.");
                return;
            }

            let finalWeight = w;
            let finalHeight = h;

            if (this.unit === "imperial") {
                finalWeight = w * 0.453592; // lbs to kg
                finalHeight = h * 2.54;     // inches to cm
            }

            const bmi = finalWeight / Math.pow(finalHeight / 100, 2);
            this.result = parseFloat(bmi.toFixed(1));

            this.updateUI();
            this.saveToDatabase();
        },

        updateUI() {
            this.gaugePosition = Math.min(Math.max(((this.result - 10) / 30) * 100, 5), 95);

            if (this.result < 18.5) {
                this.category = 'Underweight';
                this.categoryClass = 'bg-blue-50 text-blue-500 border border-blue-100';
                this.advice = 'Consider reviewing dietary intake.';
            } else if (this.result < 25) {
                this.category = 'Normal weight';
                this.categoryClass = 'bg-green-50 text-green-500 border border-green-100';
                this.advice = 'Healthy weight range. Keep it up!';
            } else if (this.result < 30) {
                this.category = 'Overweight';
                this.categoryClass = 'bg-orange-50 text-orange-500 border border-orange-100';
                this.advice = 'Consider increasing physical activity.';
            } else {
                this.category = 'Obese';
                this.categoryClass = 'bg-red-50 text-red-500 border border-red-100';
                this.advice = 'Consult with a healthcare professional.';
            }
        },

        saveToDatabase() {
            fetch("{{ route('bmi.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    patient_name: this.patientName,
                    age: this.age,
                    gender: this.gender,
                    weight: this.weight,
                    height: this.height,
                    bmi: this.result,
                    category: this.category
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    // Small delay to let the user see the result before refresh
                    setTimeout(() => { window.location.reload(); }, 1500);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }
}
</script>
@endsection