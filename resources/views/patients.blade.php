@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Patients Record</h1>
            <p class="text-gray-500 text-sm">Manage patient information and medical records</p>
        </div>
        <button onclick="openAddModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-bold flex items-center gap-2 transition shadow-lg shadow-blue-200">
            <i class="fa-solid fa-plus"></i> New Patient
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold">
                    <tr>
                        <th class="px-6 py-4">Patient ID</th>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Age</th>
                        <th class="px-6 py-4">Gender</th>
                        <th class="px-6 py-4">Address</th>
                        <th class="px-6 py-4 text-center uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($patients as $patient)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4 text-sm font-medium text-slate-600">{{ $patient->patient_id }}</td>
                        <td class="px-6 py-4 text-sm font-bold text-slate-800">{{ $patient->name }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $patient->age }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $patient->gender }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $patient->address }}</td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-4 text-slate-400">
                                <a href="{{ route('patients.show', $patient->id) }}" title="View Profile" class="hover:text-blue-500 transition">
                                    <i class="fa-solid fa-eye text-lg"></i>
                                </a>

                                <button onclick="editPatient(this)" data-patient='@json($patient)' title="Edit" class="hover:text-orange-400 transition">
                                    <i class="fa-solid fa-pen text-lg"></i>
                                </button>

                                <a href="{{ route('patients.history', $patient->id) }}" title="Clinical Records" class="hover:text-slate-600 transition">
                                    <i class="fa-solid fa-file-lines text-lg"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4">No patients found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="patientModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="toggleModal('patientModal')"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 id="modalTitle" class="text-xl font-bold text-slate-800">Add New Patient</h3>
                <button onclick="toggleModal('patientModal')" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>

            <form id="patientForm" action="{{ route('patients.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div id="methodContainer"></div> <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Full Name</label>
                    <input type="text" name="name" id="field_name" required class="w-full px-4 py-2 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-blue-400 outline-none">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Age</label>
                        <input type="number" name="age" id="field_age" required class="w-full px-4 py-2 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-blue-400 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Gender</label>
                        <select name="gender" id="field_gender" required class="w-full px-4 py-2 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-blue-400 outline-none">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Address</label>
                    <input type="text" name="address" id="field_address" required class="w-full px-4 py-2 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-blue-400 outline-none">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Service</label>
                        <input type="text" name="service" id="field_service" required class="w-full px-4 py-2 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-blue-400 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Last Visit</label>
                        <input type="date" name="last_visit" id="field_last_visit" required class="w-full px-4 py-2 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-blue-400 outline-none">
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="toggleModal('patientModal')" class="flex-1 px-4 py-3 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition">Cancel</button>
                    <button type="submit" class="flex-1 px-4 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-200 transition">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleModal(modalID) {
        document.getElementById(modalID).classList.toggle("hidden");
    }

    function openAddModal() {
        document.getElementById('modalTitle').innerText = "Add New Patient";
        const form = document.getElementById('patientForm');
        form.action = "{{ route('patients.store') }}";
        document.getElementById('methodContainer').innerHTML = ""; 
        form.reset();
        toggleModal('patientModal');
    }

    function editPatient(button) {
        // 1. Get the patient object from the button's data attribute
        const patient = JSON.parse(button.getAttribute('data-patient'));

        document.getElementById('modalTitle').innerText = "Edit Patient Record";
        
        // 2. Correct URL: /patients/1
        document.getElementById('patientForm').action = "/patients/" + patient.id; 
        
        // 3. Add PUT method for Laravel
        document.getElementById('methodContainer').innerHTML = '<input type="hidden" name="_method" value="PUT">';

        // 4. Populate the fields
        document.getElementById('field_name').value = patient.name;
        document.getElementById('field_age').value = patient.age;
        document.getElementById('field_gender').value = patient.gender;
        document.getElementById('field_address').value = patient.address;
        document.getElementById('field_service').value = patient.service;
        document.getElementById('field_last_visit').value = patient.last_visit;

        toggleModal('patientModal');
    }
</script>
@endsection