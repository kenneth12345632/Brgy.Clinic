@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-4 py-8">

```
<div class="mb-8">
    <a href="{{ route('services.index') }}" class="text-blue-600 hover:text-blue-800 font-medium flex items-center gap-2 transition">
        <i class="fa-solid fa-arrow-left text-sm"></i> Back to All Services
    </a>

    <div class="mt-4 flex justify-between items-end">
        <div>
            <h1 class="text-4xl font-extrabold text-slate-800">{{ $serviceName }}</h1>
            <p class="text-slate-500 mt-1">List of patients registered under this health service</p>
        </div>

        <div class="bg-blue-50 px-6 py-3 rounded-2xl border border-blue-100 text-right">
            <p class="text-blue-600 text-xs font-bold uppercase tracking-wider">Total Patients</p>
            <p class="text-2xl font-black text-blue-800">{{ $patients->count() }}</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">

        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4 font-bold text-slate-600 text-sm uppercase">Patient Name</th>
                    <th class="px-6 py-4 font-bold text-slate-600 text-sm uppercase text-center">Age</th>
                    <th class="px-6 py-4 font-bold text-slate-600 text-sm uppercase">Gender</th>
                    <th class="px-6 py-4 font-bold text-slate-600 text-sm uppercase">Address</th>
                    <th class="px-6 py-4 font-bold text-slate-600 text-sm uppercase text-right">Date Registered</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-50">

                @forelse($patients as $patient)

                    @php
                        $age = \Carbon\Carbon::parse($patient->birthday)->age;
                    @endphp

                    <tr class="hover:bg-slate-50/50 transition-colors">

                        <td class="px-6 py-4 font-bold text-slate-800">
                            {{ $patient->first_name }}
                            {{ $patient->middle_name }}
                            {{ $patient->last_name }}
                        </td>

                        <td class="px-6 py-4 text-slate-600 text-center">
                            {{ $age }}
                        </td>

                        <td class="px-6 py-4 text-slate-600">
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase 
                                {{ $patient->gender == 'Male' ? 'bg-blue-50 text-blue-600' : 'bg-pink-50 text-pink-600' }}">
                                {{ $patient->gender }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-slate-500">
                            {{ $patient->address }}
                        </td>

                        <td class="px-6 py-4 text-slate-500 text-sm text-right">
                            {{ $patient->created_at->format('M d, Y') }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fa-solid fa-user-slash text-slate-200 text-5xl mb-4"></i>
                                <p class="text-slate-400 font-medium">
                                    No patients found for this service.
                                </p>
                            </div>
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>
</div>
```

</div>
@endsection
