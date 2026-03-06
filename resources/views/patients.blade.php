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
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($patients as $patient)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4 text-sm font-medium text-slate-600">{{ $patient->patient_id }}</td>
                        <td class="px-6 py-4 text-sm font-bold text-slate-800">{{ $patient->first_name }} {{ $patient->last_name }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $patient->age }} Years Old</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $patient->gender }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $patient->address }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-4 text-slate-400">
                               <a href="{{ route('patients.show', $patient->patient_id) }}" title="View Profile" class="hover:text-blue-500 transition">
                                    <i class="fa-solid fa-eye text-lg"></i>
                                </a>
                                <button onclick="editPatient(this)" data-patient='@json($patient)' title="Edit" class="hover:text-orange-400 transition">
                                    <i class="fa-solid fa-pen text-lg"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-10 text-slate-400 italic">No patients found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL SECTION --}}
<div id="patientModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="toggleModal('patientModal')"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl max-w-2xl w-full overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-slate-50">
                <h3 id="modalTitle" class="text-xl font-bold text-slate-800">Add New Patient</h3>
                <button onclick="toggleModal('patientModal')" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>

            <form id="patientForm" action="{{ route('patients.store') }}" method="POST" class="p-8 space-y-5">
                @csrf
                <div id="methodContainer"></div> 
                
                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-12 md:col-span-4">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">First Name</label>
                        <input type="text" name="first_name" id="field_first_name" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                    <div class="col-span-12 md:col-span-4">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Middle Name</label>
                        <input type="text" name="middle_name" id="field_middle_name" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                    <div class="col-span-12 md:col-span-4">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Last Name</label>
                        <input type="text" name="last_name" id="field_last_name" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Birthday</label>
                        <input type="date" name="birthday" id="field_birthday" oninput="calculateAge(this.value)" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Calculated Age</label>
                        <div class="w-full px-4 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-bold border border-slate-200">
                            <span id="display_age">0</span> Years Old
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Gender</label>
                        <select name="gender" id="field_gender" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-400">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Inquired Service</label>
                        <select name="service" id="field_service" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-400">
                            <option value="Immunization">Immunization</option>
                            <option value="Family Planning">Family Planning</option>
                            <option value="Deworming">Deworming</option>
                            <option value="Pre-natal">Pre-natal</option>
                            <option value="Ferrous">Ferrous</option>
                            <option value="Nutrition">Nutrition</option>
                            <option value="Dental">Dental</option>
                            <option value="Free Consultation">Free Consultation</option>
                            <option value="Laboratory">Laboratory</option>
                            <option value="Emergency">Emergency</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Address</label>
                    <input type="text" name="address" id="field_address" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                <div class="flex gap-3 pt-6 border-t border-slate-100">
                    <button type="button" onclick="toggleModal('patientModal')" class="flex-1 px-4 py-3 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition">Cancel</button>
                    <button type="submit" class="flex-1 px-4 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-200 transition">Save Patient</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleModal(modalID) {
        document.getElementById(modalID).classList.toggle("hidden");
    }

    function calculateAge(birthDate) {
        if (!birthDate) {
            document.getElementById('display_age').innerText = "0";
            return;
        }
        const today = new Date();
        const birth = new Date(birthDate);
        let age = today.getFullYear() - birth.getFullYear();
        const m = today.getMonth() - birth.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) {
            age--;
        }
        document.getElementById('display_age').innerText = age < 0 ? 0 : age;
    }

    function openAddModal() {
        document.getElementById('modalTitle').innerText = "Add New Patient";
        const form = document.getElementById('patientForm');
        form.action = "{{ route('patients.store') }}";
        document.getElementById('methodContainer').innerHTML = ""; 
        form.reset();
        document.getElementById('display_age').innerText = "0";
        toggleModal('patientModal');
    }

    function editPatient(button) {
        const patient = JSON.parse(button.getAttribute('data-patient'));

        document.getElementById('modalTitle').innerText = "Edit Patient Record";
        document.getElementById('patientForm').action = "/patients/" + patient.id; 
        document.getElementById('methodContainer').innerHTML = '<input type="hidden" name="_method" value="PUT">';

        document.getElementById('field_first_name').value = patient.first_name;
        document.getElementById('field_middle_name').value = patient.middle_name || '';
        document.getElementById('field_last_name').value = patient.last_name;
        document.getElementById('field_birthday').value = patient.birthday;
        
        // Trigger age calculation so the display box updates immediately
        calculateAge(patient.birthday);

        document.getElementById('field_gender').value = patient.gender;
        document.getElementById('field_address').value = patient.address;
        document.getElementById('field_service').value = patient.service;

        toggleModal('patientModal');
    }
</script>
@endsection