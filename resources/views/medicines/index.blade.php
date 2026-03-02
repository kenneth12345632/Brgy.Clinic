@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    {{-- Header Section --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-4xl font-extrabold text-slate-800 tracking-tight">Medicines Inventory</h1>
            <p class="text-slate-500 mt-1">Track and manage medicine stock levels</p>
        </div>
        <button onclick="openModal('addMedicineModal')" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-bold flex items-center gap-2 transition shadow-lg shadow-blue-100">
            <i class="fa-solid fa-plus text-sm"></i> Add Medicine
        </button>
    </div>

    {{-- System Alerts --}}
    @if(session('success'))
    <div class="bg-green-50 border border-green-100 p-4 rounded-2xl mb-6 flex items-center gap-4 text-green-800 animate-fade-in">
        <i class="fa-solid fa-circle-check text-xl"></i>
        <p class="font-medium">{{ session('success') }}</p>
    </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex justify-between items-center">
            <div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-wider">Total Items</p>
                <p class="text-3xl font-black text-slate-800 mt-2">{{ $stats['total_items'] ?? 0 }}</p>
            </div>
            <div class="text-blue-500 bg-blue-50 p-3 rounded-2xl"><i class="fa-solid fa-box-archive text-xl"></i></div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex justify-between items-center">
            <div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-wider">Total Stock</p>
                <p class="text-3xl font-black text-slate-800 mt-2">{{ number_format($stats['total_stock'] ?? 0) }}</p>
            </div>
            <div class="text-green-500 bg-green-50 p-3 rounded-2xl"><i class="fa-solid fa-cubes text-xl"></i></div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex justify-between items-center">
            <div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-wider">Low Stock</p>
                <p class="text-3xl font-black text-orange-600 mt-2">{{ $stats['low_stock'] ?? 0 }}</p>
            </div>
            <div class="text-orange-500 bg-orange-50 p-3 rounded-2xl"><i class="fa-solid fa-triangle-exclamation text-xl"></i></div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex justify-between items-center">
            <div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-wider">Categories</p>
                <p class="text-3xl font-black text-slate-800 mt-2">{{ $stats['categories'] ?? 0 }}</p>
            </div>
            <div class="text-purple-500 bg-purple-50 p-3 rounded-2xl"><i class="fa-solid fa-tags text-xl"></i></div>
        </div>
    </div>

    {{-- Medicine List Table --}}
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="text-xl font-bold text-slate-800">Medicine List</h2>
            <div class="relative w-full md:w-72">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" id="medicineSearch" placeholder="Search medicines..." 
                       class="pl-11 pr-4 py-2 bg-slate-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-blue-500 w-full transition-all">
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="medicineTable">
                <thead>
                    <tr class="text-slate-700 text-sm font-bold border-b border-slate-50">
                        <th class="px-8 py-5 whitespace-nowrap">Medicine ID</th>
                        <th class="px-4 py-5 whitespace-nowrap">Name</th>
                        <th class="px-4 py-5 whitespace-nowrap">Category</th>
                        <th class="px-4 py-5 text-center whitespace-nowrap">Stock</th>
                        <th class="px-4 py-5 text-center whitespace-nowrap">Min. Stock</th>
                        <th class="px-4 py-5 whitespace-nowrap">Expiry Date</th>
                        <th class="px-4 py-5 whitespace-nowrap">Status</th>
                        <th class="px-8 py-5 text-center whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($medicines as $med)
                    <tr class="medicine-row hover:bg-slate-50/50 transition-colors">
                        <td class="px-8 py-5 font-bold text-slate-800 text-sm whitespace-nowrap">
                            {{ $med->medicine_id }}
                        </td>
                        <td class="px-4 py-5 text-slate-600 font-medium uppercase whitespace-nowrap">{{ $med->name }}</td>
                        <td class="px-4 py-5 whitespace-nowrap">
                            <span class="px-4 py-1.5 rounded-full border border-slate-200 text-slate-600 text-xs font-semibold">
                                {{ $med->category }}
                            </span>
                        </td>
                        <td class="px-4 py-5 text-center font-bold whitespace-nowrap {{ $med->stock <= ($med->min_stock ?? 10) ? 'text-orange-500' : 'text-slate-700' }}">
                            {{ $med->stock }}
                        </td>
                        <td class="px-4 py-5 text-center text-slate-600 font-medium whitespace-nowrap">{{ $med->min_stock }}</td>
                        <td class="px-4 py-5 text-slate-600 font-medium whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($med->expiry_date)->format('Y-m-d') }}
                        </td>
                        <td class="px-4 py-5 whitespace-nowrap">
                            @if($med->stock <= $med->min_stock)
                                <span class="bg-orange-50 text-orange-600 px-4 py-1.5 rounded-lg text-[11px] font-bold">Low Stock</span>
                            @else
                                <span class="bg-green-50 text-green-600 px-4 py-1.5 rounded-lg text-[11px] font-bold">In Stock</span>
                            @endif
                        </td>
                        <td class="px-8 py-5 whitespace-nowrap">
                            <div class="flex items-center justify-center gap-2">
                                {{-- FIX: Corrected onclick syntax --}}
                              {{-- Change this line in your table loop --}}
<button onclick="openEditModal('{{ $med->toJson() }}')" 
        class="border border-slate-200 text-slate-700 px-4 py-1.5 rounded-lg text-xs font-bold hover:bg-slate-50 transition whitespace-nowrap">
    Edit
</button>
                                <button onclick="openRestockModal('{{ $med->id }}', '{{ $med->name }}')" 
                                        class="border border-slate-200 text-slate-700 px-4 py-1.5 rounded-lg text-xs font-bold hover:bg-slate-50 transition whitespace-nowrap">
                                    Restock
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="px-8 py-12 text-center text-slate-400">No medicines found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODALS SECTION --}}

{{-- MODAL: ADD MEDICINE --}}
<div id="addMedicineModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-lg rounded-[2.5rem] p-8 shadow-2xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-black text-slate-800">Add Medicine</h3>
            <button onclick="closeModal('addMedicineModal')" class="text-slate-400 hover:text-red-500 transition-colors"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>
        <form action="{{ route('medicines.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-black uppercase text-slate-500 mb-2 ml-1">Name</label>
                <input type="text" name="name" required class="w-full bg-slate-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-black uppercase text-slate-500 mb-2 ml-1">Category</label>
                    <select name="category" class="w-full bg-slate-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-blue-500 font-bold">
                        <option value="Analgesic">Analgesic</option>
                        <option value="Antibiotic">Antibiotic</option>
                        <option value="Supplement">Supplement</option>
                        <option value="Antiparasitic">Antiparasitic</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-black uppercase text-slate-500 mb-2 ml-1">Initial Stock</label>
                    <input type="number" name="stock" required class="w-full bg-slate-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-black uppercase text-slate-500 mb-2 ml-1">Min. Stock</label>
                    <input type="number" name="min_stock" required value="100" class="w-full bg-slate-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-black uppercase text-slate-500 mb-2 ml-1">Expiry Date</label>
                    <input type="date" name="expiry_date" required class="w-full bg-slate-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white p-4 mt-4 rounded-2xl font-black uppercase shadow-lg hover:bg-blue-700">Save Medicine</button>
        </form>
    </div>
</div>

{{-- MODAL: EDIT MEDICINE --}}
<div id="editMedicineModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-lg rounded-[2.5rem] p-8 shadow-2xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-black text-slate-800">Edit Medicine</h3>
            <button onclick="closeModal('editMedicineModal')" class="text-slate-400 hover:text-red-500 transition-colors"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>
        <form id="editMedicineForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-black uppercase text-slate-500 mb-2 ml-1">Medicine Name</label>
                <input type="text" name="name" id="edit_name" required class="w-full bg-slate-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-black uppercase text-slate-500 mb-2 ml-1">Category</label>
                    <select name="category" id="edit_category" class="w-full bg-slate-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-blue-500 font-bold">
                        <option value="Analgesic">Analgesic</option>
                        <option value="Antibiotic">Antibiotic</option>
                        <option value="Supplement">Supplement</option>
                        <option value="Antiparasitic">Antiparasitic</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-black uppercase text-slate-500 mb-2 ml-1">Expiry Date</label>
                    <input type="date" name="expiry_date" id="edit_expiry" required class="w-full bg-slate-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div>
                <label class="block text-xs font-black uppercase text-slate-500 mb-2 ml-1">Min Alert Level</label>
                <input type="number" name="min_stock" id="edit_min_stock" required class="w-full bg-slate-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white p-4 mt-4 rounded-2xl font-black uppercase shadow-lg hover:bg-blue-700">Update Medicine</button>
        </form>
    </div>
</div>

{{-- MODAL: RESTOCK --}}
<div id="restockModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-md rounded-[2.5rem] p-8 shadow-2xl text-center">
        <div class="bg-blue-50 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-box-open text-blue-600 text-2xl"></i>
        </div>
        <h3 class="text-2xl font-black text-slate-800 mb-2">Restock Item</h3>
        <p id="restock_med_name" class="text-slate-500 mb-6 font-bold uppercase tracking-tight text-sm"></p>
        <form id="restockForm" method="POST">
            @csrf
            <div class="relative mb-4">
                <input type="number" name="amount" required min="1" placeholder="0" class="w-full bg-slate-50 border-none rounded-2xl p-6 text-center text-3xl font-black text-blue-600">
                <span class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-400 font-bold uppercase text-xs">Units</span>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white p-4 rounded-2xl font-black uppercase shadow-lg shadow-blue-100 hover:bg-blue-700">Confirm Restock</button>
            <button type="button" onclick="closeModal('restockModal')" class="mt-4 text-slate-400 font-bold hover:text-slate-600">Cancel</button>
        </form>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.getElementById(id).classList.add('flex');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.getElementById(id).classList.remove('flex');
    }

    function openEditModal(medicine) {
        const form = document.getElementById('editMedicineForm');
        form.action = `/medicines/${medicine.id}`;
        document.getElementById('edit_name').value = medicine.name;
        document.getElementById('edit_category').value = medicine.category;
        document.getElementById('edit_expiry').value = medicine.expiry_date;
        document.getElementById('edit_min_stock').value = medicine.min_stock;
        openModal('editMedicineModal');
    }

    function openRestockModal(id, name) {
        document.getElementById('restock_med_name').innerText = name;
        document.getElementById('restockForm').action = `/medicines/${id}/restock`;
        openModal('restockModal');
    }

    document.getElementById('medicineSearch').addEventListener('keyup', function() {
        let searchValue = this.value.toLowerCase();
        let rows = document.querySelectorAll('.medicine-row');
        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(searchValue) ? "" : "none";
        });
    });
</script>
@endsection